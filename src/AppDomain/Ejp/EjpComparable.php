<?php

namespace AppDomain\Ejp;

interface EjpComparable {
    public function getManuscriptNo(): int;

    public function getCorrespondingAuthor(): string;

    public function getArticleTypeCode(): string;

    public function getSubjectAreaIds(): array;

    public function getRevision();

    public function hasHadAppeal();

    public function getInsightDecision();

    public function getInsightComment();
};