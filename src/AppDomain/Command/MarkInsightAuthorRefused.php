<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class MarkInsightAuthorAsked extends AbstractPaperCommand
{
    /**
     * @var string, nullable=true
     */
    public $insightAuthorRefusalReason;
}