<?php

namespace AppDomain\Ejp;

interface EjpComparable {
    public function getManuscriptNo();

    public function getCorrespondingAuthor();

    public function getArticleTypeCode();

    public function getSubjectAreaIds();

    public function getRevision();

    public function hasHadAppeal();

    public function getInsightDecision();

    public function getInsightComment();

    public function getDigestAnswersGiven();

    public function getImpactStatement();

    public function getAbstract();

    public function getTitle();

    /**
     * @return \DateTime | null
     */
    public function getAcceptedDate();
};