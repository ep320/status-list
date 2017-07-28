<?php


namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * @ORM\Entity
 */
class InsightCommissioned extends PaperEvent
{
    /**
     * DigestWriterAssigned constructor.
     * @param string $paperId
     * @param int $sequence
     * @param int $insightManuscriptNo
     * @param DateTime $insightDueDate
     * @param string $insightMiscellaneousComment
     */
    public function __construct(string $paperId, int $sequence, int $insightManuscriptNo, \DateTime $insightDueDate, $insightMiscellaneousComment)
    {
        parent::__construct($paperId, $sequence, [
            'insightManuscriptNo' => $insightManuscriptNo,
            'insightDueDate' => $insightDueDate,
            'insightCommissionedComment' => $insightMiscellaneousComment
        ]);
    }

    public function getInsightManuscriptNo()
    {
        return $this->getFromPayload('insightManuscriptNo');
    }

    public function getInsightDueDate()
    {
        return $this->getDateTimeFromPayload('insightDueDate');
    }

    public function getInsightMiscellaneousComment()
    {
        return $this->getFromPayload('insightCommissionedComment');
    }
}