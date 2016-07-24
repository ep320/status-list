<?php

namespace AppDomain;

use AppDomain\Command\AddCommentCommand;
use AppDomain\Command\AddPaper;
use AppDomain\Event\PaperAdded;

class CommandHandler {
    /**
     * @var EventPublisher;
     */
    private $eventPublisher;

    public function __construct(EventPublisher $eventPublisher) {
        $this->eventPublisher = $eventPublisher;
    }

    /**
     * Validate an AddPaper command, and publish PaperAdded on success
     *
     * @param AddPaper $command
     */
    public function addPaper(AddPaper $command) {
        
        $subjectAreas = [$command->subjectArea1];
        if ($command->subjectArea2);
        $subjectAreas[] = $command->subjectArea2;

        $event = (new PaperAdded(
            $command->manuscriptNo,
            $command->correspondingAuthor,
            $command->articleType,
            $subjectAreas
        ));

        $this->eventPublisher->publish($event);
    }
}