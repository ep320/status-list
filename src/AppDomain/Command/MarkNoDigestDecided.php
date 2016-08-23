<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class MarkNoDigestDecided {

    /**
     * @var string
     */
    public $paperId;

    /**
     * @var string
     * @Assert\Choice(choices={"Author refused", "Questions not asked", "No response from author", "Features team decision"})
     */
    public $noDigestReason;


}