<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class MarkNoDigestDecided extends AbstractPaperCommand
{
    /**
     * @var string
     * @Assert\Choice(choices={"Author refused", "Questions not asked", "No response from author", "Features team decision"})
     */
    public $noDigestReason;
}