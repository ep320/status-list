<?php

namespace AppDomain\Ejp;

class EjpHasher
{
    static function hash(EjpComparable $ejp)
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
                $ejp->getDigestAnswers(),
                $ejp->getImpactStatement(),
                $ejp->getAbstract(),
                $ejp->getTitle(),
                $ejp->getAcceptedDate()
            ])
        );
    }
}