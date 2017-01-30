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
     * @param string $insightAuthor
     * @param string $insightAuthorRefusalReason
     */
    public function __construct(string $paperId, int $sequence, string $insightAuthor, string $insightAuthorRefusalReason)
    {
        parent::__construct($paperId, $sequence, [
            'insightAuthor' => $insightAuthor,
            'insightAuthorRefusalReason' => $insightAuthorRefusalReason
        ]);
    }

    public function getinsightAuthor()
    {
        return $this->getFromPayload('insightAuthor');
    }

    public function getinsightAuthorRefusalReason()
    {
        return $this->getFromPayload('insightAuthorRefusalReason');
    }

}