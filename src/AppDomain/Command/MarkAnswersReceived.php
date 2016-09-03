<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class MarkAnswersReceived extends AbstractPaperCommand
{
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