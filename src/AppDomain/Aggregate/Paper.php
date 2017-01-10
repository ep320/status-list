<?php
namespace AppDomain\Aggregate;

use AppDomain\Event\EjpPaperImported;
use AppDomain\Event\PaperAcceptedEvent;
use AppDomain\Event\PaperEvent;

class Paper {
    private $ejpHashForComparison;

    private $version = 0;
    private $accepted;

    /**
     * @return mixed
     */
    public function getEjpHashForComparison()
    {
        return $this->ejpHashForComparison;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function isAccepted()
    {
        return $this->accepted;
    }

    /**
     * @param mixed $accepted
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;
    }



    /**
     * @param PaperEvent[] $events
     * @return Paper
     */
    static function load($events) {
        $instance = new self();
        foreach ($events as $event) {
            $instance->applyEvent($event);
        }
        return $instance;
    }

    public function applyEvent(PaperEvent $event) {
        if ($event instanceof EjpPaperImported) {
            /** @var $event PaperEvent */
            $this->ejpHashForComparison = $event->getEjpHash();
        }
        if ($event instanceof PaperAcceptedEvent){
            $this->accepted=true;
            /** @var $event PaperEvent */
            $this->ejpHashForComparison = $event->getEjpHash();
            var_dump($this->ejpHashForComparison);
        }
        $this->version = $event->getSequence();
    }
}