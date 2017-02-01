<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class InsightAuthorChecking extends AbstractPaperCommand
{
    /**
     * @var \DateTime
     */
    public $insightEditsDueDate;
}