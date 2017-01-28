<?php

namespace AppBundle\EJPImport;

use AppBundle\Entity\SubjectArea;
use AppDomain\Ejp\EjpPaper;
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
        $headers = $reader->getColumnHeaders();
        if (count($headers) === 14) {
            return $this->parseAcceptedPaperCSV($reader);
        }

        return $this->parseRevisedPaperCSV($reader);
    }

    private function parseRevisedPaperCSV(CSVReader $reader)
    {
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

// the following currently only handles accepted papers that have already been imported on a previous occasion
    private function parseAcceptedPaperCSV(CSVReader $reader)
    {
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
                $row['MS No.'], $matches);

            var_dump($matches);

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