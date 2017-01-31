<?php

namespace AppDomain\Event;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class InsightAuthorRefused extends PaperEvent
{

    /**
     * AnswersReceived constructor.
     * @param string $paperId
     * @param int $sequence
     * @param string $insightAuthorRefusalReason
     */
    public function __construct(string $paperId, int $sequence, string $insightAuthorRefusalReason)
    {
        parent::__construct($paperId, $sequence, [
            'insightAuthorRefusalReason' => $insightAuthorRefusalReason
        ]);
    }


    public function getinsightAuthorRefusalReason()
    {
        return $this->getFromPayload('insightAuthorRefusalReason');
    }

}