<?php

namespace AppDomain\Event;

use AppBundle\Entity\ArticleType;
use AppBundle\Entity\SubjectArea;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class AnswersReceived extends PaperEvent {

    /**
     * AnswersReceived constructor.
     * @param string $paperId
     * @param int $sequence
     * @param string $answersQuality
     */
    public function __construct(string $paperId, int $sequence, string $answersQuality)
    {
        parent::__construct($paperId, $sequence, [
            'answersQuality'=>$answersQuality
        ]);
    }

    public function getAnswersQuality() {
        return $this->getFromPayload('answersQuality');
    }
}