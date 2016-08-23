<?php

namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class DigestReceived extends PaperEvent
{
    /**
     * DigestWriterAssigned constructor.
     * @param string $paperId
     * @param int $sequence
     * @param bool $digestReceived
     */
    public function __construct(string $paperId, int $sequence, bool $digestReceived)
    {
        parent::__construct($paperId, $sequence, [
            'digestReceived' => $digestReceived
        ]);
    }

    public function getDigestReceived()
    {
        return $this->getFromPayload('digestReceived');
    }

}