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
     * DigestWriterAssigned constructor.
     * @param string $paperId
     * @param int $sequence
     * @param string $insightEditor
     */
    public function __construct(string $paperId, int $sequence, string $insightEditor)
    {
        parent::__construct($paperId, $sequence, [
            'insightEditor' => $insightEditor
        ]);
    }

    public function getInsightEditorAssigned()
    {
        return $this->getFromPayload('insightEditor');
    }


}