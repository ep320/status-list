<?php

namespace AppDomain\Event;

use AppDomain\Ejp\EjpPaper;
use AppDomain\Ejp\EjpHasher;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class EjpPaperImported extends PaperEvent
{
    public function __construct(string $paperId, int $sequence, EjpPaper $ejpPaper)
    {
        parent::__construct($paperId, $sequence, [
            'manuscriptNo' => $ejpPaper->getManuscriptNo(),
            'author' => $ejpPaper->getCorrespondingAuthor(),
            'articleTypeCode' => $ejpPaper->getArticleTypeCode(),
            'revision' => $ejpPaper->getRevision(),
            'hadAppeal' => $ejpPaper->hasHadAppeal(),
            'subjectAreaIds' => $ejpPaper->getSubjectAreaIds(),
            'insightDecision' => $ejpPaper->getInsightDecision(),
            'insightComment' => $ejpPaper->getInsightComment(),
            'digestAnswersGiven' => $ejpPaper->getDigestAnswersGiven(),
            'ejpHash' => EjpHasher::hash($ejpPaper)
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

    public function getDigestAnswersGiven()
    {
        return $this->getFromPayload('digestAnswersGiven');
    }

    public function getEjpHash()
    {
        return $this->getFromPayload('ejpHash');
    }
}