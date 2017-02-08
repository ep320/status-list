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
     */
    public function __construct(string $paperId, int $sequence, string $insightAuthor)
    {
        parent::__construct($paperId, $sequence, [
            'activeInsightAuthor' => $insightAuthor,
        ]);
    }

    public function getActiveInsightAuthor()
    {
        return $this->getFromPayload('activeInsightAuthor');
    }

}