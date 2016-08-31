<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class ImportPaperDetails
{
    /**
     *
     */
    public $manuscriptNo;

    /**
     *
     */
    public $dateAdded;


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