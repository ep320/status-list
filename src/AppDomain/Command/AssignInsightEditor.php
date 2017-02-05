<?php
namespace AppDomain\Command;

use Symfony\Component\Validator\Constraints as Assert;

class AssignInsightEditor extends AbstractPaperCommand
{
    /**
     * @var string
     */
    public $editorId;

    /**
     * @var string
     */
    public $insightEditor;
}