<?php

namespace AppDomain\Event;

use AppBundle\Entity\ArticleType;
use AppBundle\Entity\SubjectArea;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class PaperAdded extends PaperEvent
{

    /**
     * PaperAdded constructor.
     * @param $manuscriptNo
     * @param $author
     * @param ArticleType $articleType
     * @param $revision
     * @param $hadAppeal
     * @param SubjectArea[] $subjectAreas
     * @param $insightDecision
     * @param $insightComment
     */
    public function __construct(int $manuscriptNo, string $author, ArticleType $articleType, int $revision, bool $hadAppeal,
                                array $subjectAreas = [],
                                string $source,
                                string $insightDecision, string $insightComment)
    {
        parent::__construct(Uuid::uuid4(), 1, [
            'manuscriptNo' => $manuscriptNo,
            'author' => $author,
            'articleTypeCode' => $articleType->getCode(),
            'revision' => $revision,
            'hadAppeal' => $hadAppeal,
            'subjectAreaIds' => array_map(function (SubjectArea $subjectArea) {
                return $subjectArea->getId();
            }, $subjectAreas),
            'source' => $source,
            'insightDecision' => $insightDecision,
            'insightComment' => $insightComment
        ]);
    }

    public function getManuscriptNo()
    {
        return $this->getFromPayload('manuscriptNo');
    }

    public function getCorrespondingAuthor()
    {
        return $this->getFromPayload('author');
    }

    public function getArticleTypeCode()
    {
        return $this->getFromPayload('articleTypeCode');
    }

    public function getSubjectAreaIds()
    {
        return $this->getFromPayload('subjectAreaIds');
    }

    public function getRevision()
    {
        return $this->getFromPayload('revision');
    }

    public function getHadAppeal()
    {
        return $this->getFromPayload('hadAppeal');
    }

    public function getInsightDecision()
    {
        return $this->getFromPayload('insightDecision');
    }

    public function getInsightComment()
    {
        return $this->getFromPayload('insightComment');
    }
}