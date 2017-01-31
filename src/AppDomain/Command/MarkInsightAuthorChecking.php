<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class MarkInsightAuthorChecking extends AbstractPaperCommand
{
    /**
     * @var \DateTime
     */
    public $insightEditsDueDate;
}