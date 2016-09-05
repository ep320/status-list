<?php

namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class AssignDigestWriter extends AbstractPaperCommand
{
    /**
     * @var string
     */
    public $writerId;

    /**
     * @var \DateTime (nullable=true)
     */
    public $dueDate;

    /**
     * @var bool
     */
    public $digestReceived;
}