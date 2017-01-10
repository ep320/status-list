<?php

namespace AppDomain\Ejp;

class EjpHasher
{
    static function revisedPaperHash(EjpComparable $ejp)
    {
        return md5(
            join('#', [
                $ejp->getManuscriptNo(),
                $ejp->getCorrespondingAuthor(),
                $ejp->getArticleTypeCode(),
                $ejp->getRevision(),
                $ejp->hasHadAppeal() ? '1' : '0',
                join(',', $ejp->getSubjectAreaIds()),
                $ejp->getInsightDecision(),
                $ejp->getInsightComment(),

            ])
        );
    }

    static function AcceptedPaperHash(EjpComparable $ejp)
    {
        return md5(
            join('#', [
                $ejp->getManuscriptNo(),
                $ejp->getCorrespondingAuthor(),
                $ejp->getArticleTypeCode(),
                $ejp->getRevision(),
                $ejp->hasHadAppeal() ? '1' : '0',
                join(',', $ejp->getSubjectAreaIds()),
                $ejp->getInsightDecision(),
                $ejp->getInsightComment(),
                $ejp->getDigestAnswersGiven(),
                $ejp->getImpactStatement(),
                $ejp->getAbstract(),
                $ejp->getTitle(),
                $ejp->getAcceptedDate() ? $ejp->getAcceptedDate()->getTimestamp() : null
            ])
        );
    }
}