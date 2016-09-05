<?php
namespace AppBundle;

use AppBundle\Entity\Paper;
use AppDomain\Event\PaperAdded;
use AppDomain\Event\PaperEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PaperHandler
{
    /**
     * @var PaperRepository
     */
    private $paperRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;
    
    public function __construct(PaperRepository $paperRepository, EntityManager $entityManager)
    {
        $this->paperRepository = $paperRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param PaperEvent $paperEvent
     */
    public function handle(PaperEvent $paperEvent) {
        /**
         * @var $paper Paper|null
         */
        $paper = $this->paperRepository->find($paperEvent->getPaperId());

        if (!$paper) {
            $paper = new Paper($paperEvent->getPaperId());
            $this->entityManager->persist($paper);
        }

        $paper->applyEvent($paperEvent, $this->entityManager);
    }

    /**
     * Called magically by doctrine (ie, through a tag on this service in services.yml) after a new event is flushed to
     * the database.
     *
     * @param PaperEvent $event
     * @param LifecycleEventArgs $args
     */
    public function postPersist(PaperEvent $event, LifecycleEventArgs $args) {
        $this->handle($event);
    }
}
