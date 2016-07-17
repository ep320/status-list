<?php
namespace AppBundle\Command;

use AppBundle\Entity\ArticleType;
use AppBundle\Entity\Paper;
use AppBundle\Entity\SubjectArea;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ddeboer\DataImport\Reader\CsvReader;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('statusbase:import')
            ->setDescription('Import .csv file into statusbase')
            ->addArgument('filename', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $papersFromCSV = $this->parseCSV($input->getArgument('filename'));
        $output->writeln(sprintf('There are <info>%d</info> papers', count($papersFromCSV)));
        /**
         * @var $em EntityManager
         */
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        /**
         * @var $paperFromCSV Paper
         */
        foreach ($papersFromCSV as $paperFromCSV) {
            $output->writeln(sprintf('Handling paper <info>%05d</info>', $paperFromCSV->getManuscriptNo()));
            if ($em->getRepository(Paper::class)->findOneBy(['manuscriptNo' => $paperFromCSV->getManuscriptNo()])) {
                $output->writeln(sprintf('Paper <info>%05d</info> is present and correct', $paperFromCSV->getManuscriptNo()));
            } else {
                $output->writeln(sprintf('Adding <info>%05d</info> to table', $paperFromCSV->getManuscriptNo()));
                $em->persist($paperFromCSV);
            }
        }
        $em->flush();
    }

    protected function parseCSV($filename)
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
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
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