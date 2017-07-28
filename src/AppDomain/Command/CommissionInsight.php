<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CommissionInsight extends AbstractPaperCommand
{

    /**
     * @var integer
     */
    public $insightManuscriptNo;

    /**
     * @var \DateTime
     */
    public $insightDueDate;

    /**
     * @var string, nullable=true
     */
    public $insightMiscellaneousComment;

}