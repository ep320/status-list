<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class MarkAnswersReceived {

    /**
     * @var string
     */
    public $paperId;

    /**
     * @var string
     * @Assert\Choice(choices={"Good", "Technical"})
     */
    public $answersQuality;

    /**
     * @var boolean
     */
    public $isInDigestForm;

}