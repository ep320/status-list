<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class MarkInsightNotCommissioned extends AbstractPaperCommand
{
    /**
     * @var string, nullable=true
     */
    public $insightNotCommissionedReason;
}