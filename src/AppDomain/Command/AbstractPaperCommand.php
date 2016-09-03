<?php
namespace AppDomain\Command;

abstract class AbstractPaperCommand {
    /**
     * @var string
     */
    public $paperId;

    /**
     * AbstractPaperCommand constructor.
     * @param string $paperId
     */
    public function __construct(string $paperId)
    {
        $this->paperId = $paperId;
    }
}