<?php

namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class PaperAccepted extends PaperEvent
{
    /**
     * DigestWriterAssigned constructor.
     * @param string $paperId
     * @param int $sequence
     * @param bool $paperAccepted
     * @param /dateTime $acceptedDate
     */
    public function __construct(string $paperId, int $sequence, bool $paperAccepted)
    {
        parent::__construct($paperId, $sequence, [
            'paperAccepted' => $paperAccepted,
            'acceptedDate' => $acceptedDate
        ]);
    }

    public function getPaperAccepted()
    {
        return $this->getFromPayload('paperAccepted');
    }

    public function getAcceptedDate()
    {
        return $this->getFromPayload('acceptedDate');
    }
}