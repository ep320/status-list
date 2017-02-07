<?php


namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * @ORM\Entity
 */
class InsightEditorAssigned extends PaperEvent
{
    /**
     * @param string $paperId
     * @param int $sequence
     * @param string $editorId
     */
    public function __construct(string $paperId, int $sequence, string $editorId)
    {
        parent::__construct($paperId, $sequence, [
            'editorId' => $editorId
        ]);
    }

    public function getInsightEditorAssigned()
    {
        return $this->getFromPayload('editorId');
    }


}