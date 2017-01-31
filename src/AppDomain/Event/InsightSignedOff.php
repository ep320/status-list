<?php

namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class InsightSignedOff extends PaperEvent
{
    /**
     * @param string $paperId
     * @param int $sequence
     * @param bool $insightSignedOff
     */
    public function __construct(string $paperId, int $sequence, bool $insightSignedOff)
    {
        parent::__construct($paperId, $sequence, [
            'insightSignedOff' => $insightSignedOff
        ]);
    }

    public function getInsightSignedOff()
    {
        return $this->getFromPayload('insightSignedOff');
    }

}