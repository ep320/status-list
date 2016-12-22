<?php

namespace AppBundle\EJPImport;

use AppBundle\Entity\SubjectArea;
use AppDomain\Ejp\EjpPaper;
use Ddeboer\DataImport\Reader\CsvReader;
use Doctrine\ORM\EntityManager;

class CSVParserForInsights
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

    public function parseCSVForInsights(\SplFileObject $file)
    {

        $reader = new CsvReader($file);
        $reader->setStrict(false);
        $reader->setHeaderRowNumber(3, CsvReader::DUPLICATE_HEADERS_INCREMENT);

        /**
         * @var $ejpPapers EjpPaper[]
         */
        $ejpPapers = [];

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

            if (array_key_exists(intval($matches['manuscriptNo']), $ejpPapers)) {
                $ejpPapers[intval($matches['manuscriptNo'])]->subjectAreaId2 = $subjectArea->getId();
                continue;
            }
            $ejpPaper = new EjpPaper();
            $ejpPaper->setManuscriptNo(intval($matches['manuscriptNo']));
            $ejpPaper->setArticleTypeCode($matches['articleType']);
            $ejpPaper->setCorrespondingAuthor(html_entity_decode($row['Corresponding Author']));
            $ejpPaper->setRevision(isset($matches['revision']) ? intval($matches['revision']) : 0);
            $ejpPaper->setHadAppeal(isset($matches['hadAppeal']) && $matches['hadAppeal'] == 'A');
            $ejpPaper->setSubjectAreaId1($subjectArea->getId());
            $ejpPaper->setInsightDecision($row['Insight?']);
            $ejpPaper->setInsightComment($row['Justification']);
            $ejpPapers[$ejpPaper->manuscriptNo] = $ejpPaper;
        }
        return $ejpPapers;
    }

}