<?php

namespace AppDomain\Event;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class AnswersReceived extends PaperEvent
{

    /**
     * AnswersReceived constructor.
     * @param string $paperId
     * @param int $sequence
     * @param string $answersQuality
     */
    public function __construct(string $paperId, int $sequence, string $answersQuality, bool $isInDigestForm)
    {
        parent::__construct($paperId, $sequence, [
            'answersQuality' => $answersQuality,
            'isInDigestForm' => $isInDigestForm
        ]);
    }

    public function getAnswersQuality()
    {
        return $this->getFromPayload('answersQuality');
    }


    public function getIsInDigestForm()
    {
        return $this->getFromPayload('isInDigestForm');
    }

}