<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class ImportPaperDetails
{
    /**
     * @var int
     */
    public $manuscriptNo;

    /**
     * @var string
     */
    public $correspondingAuthor;

    /**
     * @var string
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
     */
    public $subjectAreaId1;

    /**
     * @var int
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