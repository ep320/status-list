<?php
namespace AppDomain\Aggregate;

use AppDomain\Event\EjpPaperImported;
use AppDomain\Event\PaperEvent;

class Paper {
    private $ejpHashForComparison;

    private $version = 0;

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
        $this->version = $event->getSequence();
    }
}