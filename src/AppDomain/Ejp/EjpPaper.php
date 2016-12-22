<?php
namespace AppDomain\Ejp;

use Symfony\Component\Validator\Constraints as Assert;

class EjpPaper implements EjpComparable
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
    public $title;


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

    /**
     * @var string
     */
    public $digestAnswers;

    /**
     * @var string
     */
    public $abstract;

    /**
     * @var string
     */
    public $impactStatement;


    /**
     * @var \DateTime
     */
    public $acceptedDate;


    /**
     * @param int $manuscriptNo
     */
    public function setManuscriptNo(int $manuscriptNo)
    {
        $this->manuscriptNo = $manuscriptNo;
    }

    /**
     * @param string $correspondingAuthor
     */
    public function setCorrespondingAuthor(string $correspondingAuthor)
    {
        $this->correspondingAuthor = $correspondingAuthor;
    }

    /**
     * @param string $articleTypeCode
     */
    public function setArticleTypeCode(string $articleTypeCode)
    {
        $this->articleTypeCode = $articleTypeCode;
    }

    /**
     * @param int $revision
     */
    public function setRevision(int $revision)
    {
        $this->revision = $revision;
    }

    /**
     * @param boolean $hadAppeal
     */
    public function setHadAppeal(bool $hadAppeal)
    {
        $this->hadAppeal = $hadAppeal;
    }

    /**
     * @param int $subjectAreaId1
     */
    public function setSubjectAreaId1(int $subjectAreaId1)
    {
        $this->subjectAreaId1 = $subjectAreaId1;
    }

    /**
     * @param int $subjectAreaId2
     */
    public function setSubjectAreaId2(int $subjectAreaId2)
    {
        $this->subjectAreaId2 = $subjectAreaId2;
    }

    /**
     * @param string $insightDecision
     */
    public function setInsightDecision(string $insightDecision)
    {
        $this->insightDecision = $insightDecision;
    }

    /**
     * @param string $insightComment
     */
    public function setInsightComment(string $insightComment)
    {
        $this->insightComment = $insightComment;
    }

    /**
     * @return int
     */
    public function getManuscriptNo()
    {
        return $this->manuscriptNo;
    }

    /**
     * @return string
     */
    public function getCorrespondingAuthor()
    {
        return $this->correspondingAuthor;
    }

    /**
     * @return string
     */
    public function getArticleTypeCode()
    {
        return $this->articleTypeCode;
    }

    /**
     * @return int
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @return boolean
     */
    public function hasHadAppeal()
    {
        return $this->hadAppeal;
    }

    /**
     * @return int
     */
    public function getSubjectAreaId1()
    {
        return $this->subjectAreaId1;
    }

    /**
     * @return int
     */
    public function getSubjectAreaId2()
    {
        return $this->subjectAreaId2;
    }

    /**
     * @return int[]
     */
    public function getSubjectAreaIds()
    {
        return array_filter([$this->subjectAreaId1, $this->subjectAreaId2], function($var){return !is_null($var);} );
    }

    /**
     * @return string
     */
    public function getInsightDecision()
    {
        return $this->insightDecision;
    }

    /**
     * @return string
     */
    public function getInsightComment()
    {
        return $this->insightComment;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDigestAnswers()
    {
        return $this->digestAnswers;
    }

    /**
     * @param string $digestAnswers
     */
    public function setDigestAnswers(string $digestAnswers)
    {
        $this->digestAnswers = $digestAnswers;
    }

    /**
     * @return string
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * @param string $abstract
     */
    public function setAbstract(string $abstract)
    {
        $this->abstract = $abstract;
    }

    /**
     * @return string
     */
    public function getImpactStatement()
    {
        return $this->impactStatement;
    }

    /**
     * @param string $impactStatement
     */
    public function setImpactStatement(string $impactStatement)
    {
        $this->impactStatement = $impactStatement;
    }

    /**
     * @return \DateTime
     */
    public function getAcceptedDate()
    {
        return $this->acceptedDate;
    }

    /**
     * @param \DateTime $acceptedDate
     */
    public function setAcceptedDate(\DateTime $acceptedDate)
    {
        $this->acceptedDate = $acceptedDate;
    }


}