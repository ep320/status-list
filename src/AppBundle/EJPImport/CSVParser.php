<?php

namespace AppBundle\EJPImport;

use AppBundle\Entity\ArticleType;
use AppBundle\Entity\Paper;
use AppBundle\Entity\SubjectArea;
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
    }

    public function parseCSV($filename)
    {
        ini_set('auto_detect_line_endings', true);
        if (!file_exists($filename)) {
            throw new \Exception('File not found');
        }
        $file = new \SplFileObject($filename);
        $reader = new CsvReader($file);
        $reader->setStrict(false);
        $reader->setHeaderRowNumber(3, CsvReader::DUPLICATE_HEADERS_INCREMENT);
        $newPapers = [];

        /**
         * @var $em EntityManager
         */
        $em = $this->em;
        foreach ($reader as $row) {

            $matches = [];
            preg_match('#([A-Z]+)-\w+-([0-9]{5})#', $row['MS Tracking No.'], $matches);

            $subjectArea = $em->getRepository(SubjectArea::class)
                ->findOneBy(['description' => $row['Major Subject Area(s)']]);

            if (array_key_exists(intval($matches[2]), $newPapers)) {
                $newPapers[intval($matches[2])]->setSubjectArea2($subjectArea);
                continue;
            }
            $newPaper = new Paper;
            $newPaper->setArticleType($em->getReference(ArticleType::class, $matches[1]));
            $newPaper->setManuscriptNo(intval($matches[2]));
            $newPaper->setCorrespondingAuthor(html_entity_decode($row['Corresponding Author']));
            $newPaper->setSubjectArea1($subjectArea);
            $newPapers[$newPaper->getManuscriptNo()] = $newPaper;
        }
        return $newPapers;
    }

}