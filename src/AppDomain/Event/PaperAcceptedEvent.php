<?php

namespace AppDomain\Event;

use AppDomain\Ejp\EjpPaper;
use AppDomain\Ejp\EjpHasher;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class PaperAcceptedEvent extends PaperEvent
{

    public function __construct(string $paperId, int $sequence, EjpPaper $ejpPaper)
    {
        parent::__construct($paperId, $sequence, [
            'impactStatement' => $ejpPaper->getImpactStatement(),
            'acceptedDate' => $ejpPaper->getAcceptedDate(),
            'digestAnswersGiven' => $ejpPaper->getDigestAnswersGiven(),
            'ejpAcceptedPaperHash' => EjpHasher::AcceptedPaperHash($ejpPaper)
        ]);
    }

    public function getImpactStatement()
    {
        return $this->getFromPayload('impactStatement');
    }

    public function getAcceptedDate()
    {
        return $this->getFromPayload('acceptedDate');
    }

    public function getDigestAnswersGiven()
    {
        return $this->getFromPayload('digestAnswersGiven');
    }

    public function getEjpAcceptedPaperHash()
    {
        return $this->getFromPayload('ejpAcceptedPaperHash');
    }
}