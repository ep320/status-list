<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class DecideToNotCommissionInsight extends AbstractPaperCommand
{
    /**
     * @var string, nullable=true
     */
    public $insightNotCommissionedReason;
}