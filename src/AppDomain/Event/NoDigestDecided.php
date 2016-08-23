<?php

namespace AppDomain\Event;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class NoDigestDecided extends PaperEvent
{

    /**
     * AnswersReceived constructor.
     * @param string $paperId
     * @param int $sequence
     * @param string $noDigestReason
     */
    public function __construct(string $paperId, int $sequence, string $noDigestReason)
    {
        parent::__construct($paperId, $sequence, [
            'noDigestReason' => $noDigestReason
        ]);
    }

    public function getNoDigestReason()
    {
        return $this->getFromPayload('noDigestReason');
    }

}