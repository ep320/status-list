<?php

namespace AppDomain\Command;


use AppBundle\Entity\DigestWriter;
use Symfony\Component\Validator\Constraints as Assert;

class AssignDigestWriter extends AbstractPaperCommand
{
    /**
     * @var DigestWriter
     */
    public $writer;

    /**
     * @var \DateTime (nullable=true)
     */
    public $dueDate;

    /**
     * @var bool
     */
    public $digestReceived;
}