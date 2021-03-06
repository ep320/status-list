<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CommissionInsight extends AbstractPaperCommand
{
    /**
     * @var \DateTime
     */
    public $insightDueDate;

    /**
     * @var integer
     */
    public $insightManuscriptNo;

    /**
     * @var string, nullable=true
     */
    public $insightCommissionedComment;

}