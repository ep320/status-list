<?php

namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class InsightAcknowledged extends PaperEvent
{
    /**
     * @param string $paperId
     * @param int $sequence
     * @param bool $insightAcknowledged
     */
    public function __construct(string $paperId, int $sequence, bool $insightAcknowledged)
    {
        parent::__construct($paperId, $sequence, [
            'insightAcknowledged' => $insightAcknowledged
        ]);
    }

    public function getInsightAcknowledged()
    {
        return $this->getFromPayload('insightAcknowledged');
    }

}