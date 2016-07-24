<?php

namespace AppDomain;

use AppDomain\Event\PaperEvent;
use Doctrine\ORM\EntityManager;


class EventPublisher
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function publish(PaperEvent $event) {
        $this->entityManager->persist($event);
        $this->entityManager->flush($event);
        return;
    }
}