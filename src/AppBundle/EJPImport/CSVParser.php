<?php

namespace AppBundle\EJPImport;


use AppBundle\Entity\ArticleType;
use AppBundle\Entity\SubjectArea;
use AppDomain\Command\ImportPaperDetails;
use Ddeboer\DataImport\Reader\CsvReader;
use Doctrine\ORM\EntityManager;

class CSVParser
{

    private $em;

    /**
     * CSVParser constructor.
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        ini_set('auto_detect_line_endings', true);

    }

    public function parseCSV(\SplFileObject $file)
    {

        $reader = new CsvReader($file);
        $reader->setStrict(false);
        $reader->setHeaderRowNumber(3, CsvReader::DUPLICATE_HEADERS_INCREMENT);

        /**
         * @var $importCommands ImportPaperDetails[]
         */
        $importCommands = [];

        /**
         * @var $em EntityManager
         */
        $em = $this->em;

        if (!in_array('MS Tracking No.', $reader->getColumnHeaders())) {
            throw new \Exception('Please upload a correctly formatted .csv file (column headers in 4th row');

        }

        foreach ($reader as $row) {

            $matches = [];
            preg_match('#(?<articleType>[A-Z]+)-\w+-(?<manuscriptNo>[0-9]{5})(R(?<revision>\d+))?(-(?<hadAppeal>\w))?#',
                $row['MS Tracking No.'], $matches);

            /**
             * @var $subjectArea SubjectArea
             */
            $subjectArea = $em->getRepository(SubjectArea::class)
                ->findOneBy(['description' => $row['Major Subject Area(s)']]);

            if (array_key_exists(intval($matches['manuscriptNo']), $importCommands)) {
                $importCommands[intval($matches['manuscriptNo'])]->subjectAreaId2 = $subjectArea->getId();
                continue;
            }
            $importCommand = new ImportPaperDetails();
            $importCommand->manuscriptNo = intval($matches['manuscriptNo']);
            $importCommand->articleTypeCode = $matches['articleType'];
            $importCommand->correspondingAuthor = html_entity_decode($row['Corresponding Author']);
            $importCommand->revision = isset($matches['revision']) ? intval($matches['revision']) : 0;
            $importCommand->hadAppeal = isset($matches['hadAppeal']) && $matches['hadAppeal'] == 'A';
            $importCommand->subjectAreaId1 = $subjectArea->getId();
            $importCommand->insightDecision = $row['Insight?'];
            $importCommand->insightComment = $row['Justification'];
            $importCommands[$importCommand->manuscriptNo] = $importCommand;
        }
        return $importCommands;
    }

}