<?php


namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 */
class InsightAuthorAsked extends PaperEvent
{
    /**
     * InsightAuthorAsked constructor.
     * @param string $paperId
     * @param int $sequence
     * @param string $insightAuthor
     * @param string $insightCommissioningReason
     */
    public function __construct(string $paperId, int $sequence, string $insightAuthor, $insightCommissioningReason)
    {
        parent::__construct($paperId, $sequence, [
            'activeInsightAuthor' => $insightAuthor,
            'insightCommissioningReason' => $insightCommissioningReason
        ]);
    }

    public function getActiveInsightAuthor()
    {
        return $this->getFromPayload('activeInsightAuthor');
    }

    public function getInsightCommissioningReason()
    {
        return $this->getFromPayload('insightCommissioningReason');
    }
}