<?php

namespace AppDomain;

use AppDomain\Command\AddPaperManually;
use AppDomain\Command\ImportPaperDetails;
use AppDomain\Event\PaperAdded;
use AppDomain\Event\PaperEvent;
use Doctrine\ORM\EntityManager;

class CommandHandler {
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     *
     */
    private function findByManuscriptNo(int $manuscriptNo) {
        $this->entityManager->
    }

    /**
     * Validate an AddPaperManually command, and publish PaperAdded on success
     *
     * @param AddPaperManually $command
     */
    public function addPaperManually(AddPaperManually $command) {
        
        $subjectAreas = [$command->subjectArea1];
        if ($command->subjectArea2){
            $subjectAreas[] = $command->subjectArea2;
        }

        $event = (new PaperAdded(
            $command->manuscriptNo,
            $command->correspondingAuthor,
            $command->articleType,
            $subjectAreas,
            'Manual'
        ));

        $this->publish($event);

    }

    /**
     * Validate an ImportPaperDetails command, and publish PaperAdded on success
     *
     * @param ImportPaperDetails $command
     */
    public function importPaperDetails (ImportPaperDetails $command) {

        $subjectAreas = [$command->subjectArea1];
        if ($command->subjectArea2){
            $subjectAreas[] = $command->subjectArea2;
        }

        $event = (new PaperAdded(
            $command->manuscriptNo,
            $command->correspondingAuthor,
            $command->articleType,
            $subjectAreas,
            'Imported'
        ));

        $this->publish($event);

    }

    public function publish(PaperEvent $event) {
        $this->entityManager->persist($event);
        $this->entityManager->flush($event);
        return;
    }
}