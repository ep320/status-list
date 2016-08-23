<?php

namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class DigestSignedOff extends PaperEvent
{
    /**
     * @param string $paperId
     * @param int $sequence
     * @param bool $digestSignedOff
     */
    public function __construct(string $paperId, int $sequence, bool $digestSignedOff)
    {
        parent::__construct($paperId, $sequence, [
            'digestSignedOff' => $digestSignedOff
        ]);
    }

    public function getDigestSignedOff()
    {
        return $this->getFromPayload('digestSignedOff');
    }

}