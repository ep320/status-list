<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class AddPaperManually
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $manuscriptNo;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $correspondingAuthor;

    /**
     * @var string
     * @Assert\Valid()
     */
    public $articleTypeCode;

    /**
     * @var int
     */
    public $revision;

    /**
     * @var bool
     */
    public $hadAppeal;

    /**
     * @var int
     * @Assert\Valid()
     */
    public $subjectAreaId1;

    /**
     * @var int
     * @Assert\Valid()
     */
    public $subjectAreaId2;

    /**
     * @var string
     */
    public $insightDecision;

    /**
     * @var string
     */
    public $insightComment;
}