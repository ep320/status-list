<?php

namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class InsightAuthorReminded extends PaperEvent
{
    /**
     * @param string $paperId
     * @param int $sequence
     * @param bool $insightAuthorReminded
     */
    public function __construct(string $paperId, int $sequence, bool $insightAuthorReminded)
    {
        parent::__construct($paperId, $sequence, [
            'insightAuthorReminded' => $insightAuthorReminded
        ]);
    }

    public function getInsightAuthorReminded()
    {
        return $this->getFromPayload('insightAuthorReminded');
    }

}