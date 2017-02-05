<?php

namespace AppBundle\Command;

use AppDomain\Event\PaperEvent;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RebuildCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('statusbase:rebuild')
            ->setDescription('Rebuild the Papers snapshot from the event store');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return;
        /**
         * @var $doctrine RegistryInterface
         */
        $doctrine = $this->getContainer()->get('doctrine');
        /**
         * @var $em EntityManager
         */
        $papersEm = $doctrine->getEntityManager();
        $eventsEm = $doctrine->getEntityManager('events');

        $output->writeln(sprintf('<comment>Clearing snapshots table</comment>'));
        $output->writeln('');
        $papersEm->createQuery('DELETE FROM AppBundle:Paper')->execute();

        $q = $eventsEm->createQuery('SELECT e FROM AppDomain:PaperEvent e ORDER BY e.sequence ASC');
        $iterableResult = $q->iterate();
        $batchSize = 100;
        $i = 0;
        foreach ($iterableResult as $row) {
            /**
             * @var $event PaperEvent
             */
            $event = $row[0];
            $this->getContainer()->get('paper_event_handler')->handle($event);
            $output->writeln(sprintf('Handling event <comment>%s</comment> on paper <comment>%s</comment> (<comment>%s</comment>)',
                get_class($event),
                $event->getPaperId(),
                $event->getTime()->format('d-m-Y H:i:s')
            ));
            $output->writeln(sprintf('    : %s',
                json_encode($event->getPayload())
            ));

            if (($i % $batchSize) === 0) {
                $papersEm->flush(); // Executes all updates.
                $papersEm->clear(); // Detaches all objects from Doctrine!
                $eventsEm->clear(); // Detaches all objects from Doctrine!
            }
            ++$i;
        }
        $papersEm->flush();
        $output->writeln('');
        $output->writeln('<info>Done!</info>');
    }
}
