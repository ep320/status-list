<?php

namespace AppBundle\EJPImport;

use AppBundle\Command\ImportCommand;
use AppBundle\Entity\ArticleType;
use AppBundle\Entity\Paper;
use AppBundle\Entity\SubjectArea;
use AppDomain\Command\ImportPaperDetails;
use Ddeboer\DataImport\Reader\CsvReader;
use Doctrine\ORM\EntityManager;

class CSVParser {

    private $em;

    /**
     * CSVParser constructor.
     */
    public function __construct(EntityManager $em)
    {
        $this->em=$em;
        ini_set('auto_detect_line_endings', true);

    }

    public function parseCSV(\SplFileObject $file)
    {

        $reader = new CsvReader($file);
        $reader->setStrict(false);
        $reader->setHeaderRowNumber(3, CsvReader::DUPLICATE_HEADERS_INCREMENT);
        $importCommands = [];

        /**
         * @var $em EntityManager
         */
        $em = $this->em;
        foreach ($reader as $row) {

            $matches = [];
            preg_match('#([A-Z]+)-\w+-([0-9]{5})#', $row['MS Tracking No.'], $matches);

            $subjectArea = $em->getRepository(SubjectArea::class)
                ->findOneBy(['description' => $row['Major Subject Area(s)']]);

            if (array_key_exists(intval($matches[2]), $importCommands)) {
                $importCommands[intval($matches[2])]->subjectArea2 = $subjectArea;
                continue;
            }
            $importCommand = new ImportPaperDetails();
            $importCommand->manuscriptNo = intval($matches[2]);
            $importCommand->articleType = $em->getReference(ArticleType::class, $matches[1]);
            $importCommand->correspondingAuthor = html_entity_decode($row['Corresponding Author']);
            $importCommand->subjectArea1 = $subjectArea;
            $importCommands[$importCommand->manuscriptNo] = $importCommand;
        }
        return $importCommands;
    }

}