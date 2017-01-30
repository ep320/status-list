<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class MarkInsightAuthorAsked extends AbstractPaperCommand
{
    /**
     * @var string
     */
    public $insightAuthor;

    /**
     * @var \DateTime
     */
    public $insightDueDate;
}