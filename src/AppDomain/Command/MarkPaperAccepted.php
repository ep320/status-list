<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class MarkPaperAccepted extends AbstractPaperCommand
{
    /**
     * @var \DateTime (nullable=true)
     */
    public $acceptedDate;

    /**
     * @var boolean
     */
    public $paperAccepted;
}