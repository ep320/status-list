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
        ini_set('auto_detect_line_endings', true);
        $filename = $input->getArgument('filename');
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

            if (array_key_exists(intval($matches[2]), $newPapers)) {
                $newPapers[intval($matches[2])]->setSubjectArea2($em->getReference(SubjectArea::class, $row['Major Subject Area(s)']));
                continue;
            }
            $newPaper = new Paper;
            $newPaper->setArticleType($em->getReference(ArticleType::class, $matches[1]));
            $newPaper->setManuscriptNo(intval($matches[2]));
            $newPaper->setCorrespondingAuthor($row['Corresponding Author']);
            $newPaper->setSubjectArea1($em->getReference(SubjectArea::class, $row['Major Subject Area(s)']));
            $newPapers[$newPaper->getManuscriptNo()] = $newPaper;
            $em->persist($newPaper);
        }
        $em->flush();
        //$contents=file_get_contents($filename);
        $output->writeln($reader->getColumnHeaders());
    }
}