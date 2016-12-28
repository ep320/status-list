<?php

namespace AppDomain\Event;

use AppDomain\Ejp\EjpPaper;
use AppDomain\Ejp\EjpHasher;
use Doctrine\ORM\Mapping as ORM;

class PaperAccepted extends PaperEvent
{
    /**
     * @ORM\Entity
     */
    public function __construct(string $paperId, int $sequence, EjpPaper $ejpPaper)
    {
        parent::__construct($paperId, $sequence, [
            'acceptedDate' => $ejpPaper->getAcceptedDate(),
            'digestAnswersGiven' => $ejpPaper->getDigestAnswersGiven(),
            'ejpHash' => EjpHasher::hash($ejpPaper)
        ]);
    }

    public function getAcceptedDate()
    {
        return $this->getFromPayload('acceptedDate');
    }

    public function getDigestAnswersGiven()
    {
        return $this->getFromPayload('digestAnswersGiven');
    }
}