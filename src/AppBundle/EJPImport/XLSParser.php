<?php

namespace AppBundle\EJPImport;

use AppBundle\Entity\SubjectArea;
use AppDomain\Ejp\EjpPaper;
use Doctrine\ORM\EntityManager;
use Liuggio\ExcelBundle\Factory;
use PHPExcel_Cell;

class XLSParser
{

    private $em;
    private $parserFactory;

    /**
     * XLSParser constructor.
     */
    public function __construct(EntityManager $em, Factory $factory)
    {
        $this->em = $em;
        $this->parserFactory = $factory;
        ini_set('auto_detect_line_endings', true);

    }

    public function parse(string $filename)
    {
        $excelObject = $this->parserFactory->createPHPExcelObject($filename);
        $rowIterator = $excelObject->getActiveSheet()->getRowIterator(4, 4);
        $cellIterator = $rowIterator->current()->getCellIterator('A', null);
        $rows = [];

        $headers = [];
        /** @var PHPExcel_Cell $cell */
        foreach ($cellIterator as $index => $cell) {
            if ($cell->getValue() === null) {
                break;
            }
            $headers[$index] = $cell->getValue();
        }

        $rowIterator = $excelObject->getActiveSheet()->getRowIterator(5);

        /** @var \PHPExcel_Worksheet_Row $row */
        foreach ($rowIterator as $row) {
            $cellIterator = $row->getCellIterator();
            $assocRow = [];
            foreach ($cellIterator as $index => $cell) {
                if (isset($headers[$index])) {
                    $assocRow[$headers[$index]] = $cell->getValue();
                }
            }
            if (trim($assocRow['MS Tracking No.'])) {
                $rows[] = $assocRow;
            }
        }



        if (count($headers) === 14) {
            return $this->parseAcceptedPaperXLS($rows);
        }

        return $this->parseRevisedPaperXLS($rows);
    }

    private function parseRevisedPaperXLS($rows)
    {
        /**
         * @var $ejpPapers EjpPaper[]
         */
        $ejpPapers = [];

        /**
         * @var $em EntityManager
         */
        $em = $this->em;

//TODO add exception handling?
        foreach ($rows as $row) {

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

    private function parseAcceptedPaperXLS($rows)
    {
        /**
         * @var $ejpPapers EjpPaper[]
         */
        $ejpPapers = [];

        /**
         * @var $em EntityManager
         */
        $em = $this->em;

        //TODO add exception handling?

        foreach ($rows as $row) {

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
            $ejpPaper->setAbstract($row['Abstract']);
            $ejpPaper->setImpactStatement($row['Impact statement']);
            $ejpPaper->setDigestQuestionsAsked($row['Digest question?']);
            $ejpPaper->setDigestAnswersGiven($row['Digest answers?']);
            $ejpPaper->setAccepted(True);

            $matches = [];
            preg_match('#([0-9]{4}-[0-9]{2}-[0-9]{2})#',
                $row['Decision date'], $matches);

            $ejpPaper->setAcceptedDate(date_create_from_format('Y-m-d', $matches[0]));
            $ejpPapers[$ejpPaper->manuscriptNo] = $ejpPaper;
        }
        return $ejpPapers;
    }


}