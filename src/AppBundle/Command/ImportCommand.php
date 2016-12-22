<?php
namespace AppBundle\Command;

use AppBundle\EJPImport\CSVParserForInsights;
use AppBundle\Entity\Paper;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        /**
         * @var $em EntityManager
         */
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $csvParser = new CSVParserForInsights($em);
        $filename = $input->getArgument('filename');
        if (!file_exists($filename)) {
            throw new \Exception('File not found');
        }
        $file = new \SplFileObject($filename);
        $papersFromCSV = $csvParser->parseCSVForInsights($file);
        $output->writeln(sprintf('There are <info>%d</info> papers', count($papersFromCSV)));

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
}