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
    public function getManuscriptNo(): int
    {
        return $this->manuscriptNo;
    }

    /**
     * @return string
     */
    public function getCorrespondingAuthor(): string
    {
        return $this->correspondingAuthor;
    }

    /**
     * @return string
     */
    public function getArticleTypeCode(): string
    {
        return $this->articleTypeCode;
    }

    /**
     * @return int
     */
    public function getRevision(): int
    {
        return $this->revision;
    }

    /**
     * @return boolean
     */
    public function hasHadAppeal(): bool
    {
        return $this->hadAppeal;
    }

    /**
     * @return int
     */
    public function getSubjectAreaId1(): int
    {
        return $this->subjectAreaId1;
    }

    /**
     * @return int
     */
    public function getSubjectAreaId2(): int
    {
        return $this->subjectAreaId2;
    }

    /**
     * @return int[]
     */
    public function getSubjectAreaIds(): array
    {
        return array_filter([$this->subjectAreaId1, $this->subjectAreaId2], function($var){return !is_null($var);} );
    }

    /**
     * @return string
     */
    public function getInsightDecision(): string
    {
        return $this->insightDecision;
    }

    /**
     * @return string
     */
    public function getInsightComment(): string
    {
        return $this->insightComment;
    }
}