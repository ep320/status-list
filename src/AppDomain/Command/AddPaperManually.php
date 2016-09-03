<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class AddPaperManually
{
    /**
     *
     */
    public $manuscriptNo;

    /**
     *
     */
    public $correspondingAuthor;

    /**
     * @Assert\Type(type="AppBundle\Entity\ArticleType")
     * @Assert\Valid()
     */
    public $articleType;

    /**
     *
     */
    public $revision;

    /**
     *
     */
    public $hadAppeal;

    /**
     * @Assert\Type(type="AppBundle\Entity\SubjectArea")
     * @Assert\Valid()
     */
    public $subjectArea1;

    /**
     * @Assert\Type(type="AppBundle\Entity\SubjectArea")
     * @Assert\Valid()
     */
    public $subjectArea2;

    /**
     *
     */
    public $insightDecision;

    /**
     *
     */
    public $insightComment;
}