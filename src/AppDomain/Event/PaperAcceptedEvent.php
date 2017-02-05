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
            'abstract' => $ejpPaper->getAbstract(),
            'acceptedDate' => $ejpPaper->getAcceptedDate(),
            'digestQuestionsAsked' => $ejpPaper->getDigestQuestionsAsked(),
            'digestAnswersGiven' => $ejpPaper->getDigestAnswersGiven(),
            'ejpAcceptedPaperHash' => EjpHasher::AcceptedPaperHash($ejpPaper)
        ]);
    }

    public function getImpactStatement()
    {
        return $this->getFromPayload('impactStatement');
    }

    public function getAbstract()
    {
        return $this->getFromPayload('abstract');
    }


    public function getAcceptedDate()
    {
        return $this->getDateTimeFromPayload('acceptedDate');
    }

    public function getDigestQuestionsAsked()
    {
        return $this->getFromPayload('digestQuestionsAsked');
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