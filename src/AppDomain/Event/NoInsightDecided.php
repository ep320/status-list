<?php

namespace AppDomain\Event;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class NoInsightDecided extends PaperEvent
{

    /**
     * AnswersReceived constructor.
     * @param string $paperId
     * @param int $sequence
     * @param string $insightNotCommmissionedReason
     */
    public function __construct(string $paperId, int $sequence, string $insightNotCommissionedReason)
    {
        parent::__construct($paperId, $sequence, [
            'insightNotCommissionedReason' => $insightNotCommissionedReason
        ]);
    }

    public function getinsightNotCommissionedReason()
    {
        return $this->getFromPayload('insightNotCommissionedReason');
    }

}