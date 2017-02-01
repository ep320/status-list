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
     */
    public function __construct(string $paperId, int $sequence)
    {
        parent::__construct($paperId, $sequence);
    }

}