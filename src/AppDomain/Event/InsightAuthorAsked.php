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
     * @param DateTime $insightDueDate
     */
    public function __construct(string $paperId, int $sequence, string $insightAuthor, \DateTime $insightDueDate)
    {
        parent::__construct($paperId, $sequence, [
            'insightAuthor' => $insightAuthor,
            'insightDueDate' => $insightDueDate,
        ]);
    }

    public function getInsightAuthor()
    {
        return $this->getFromPayload('insightAuthor');
    }

    public function getInsightDueDate()
    {
        return $this->getDateTimeFromPayload('insightDueDate');
    }
}