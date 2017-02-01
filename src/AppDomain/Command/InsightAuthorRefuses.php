<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class InsightAuthorRefuses extends AbstractPaperCommand
{
    /**
     * @var string, nullable=true
     */
    public $insightAuthorRefusalReason;
}