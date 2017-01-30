<?php


namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * @ORM\Entity
 */
class DigestWriterAssigned extends PaperEvent
{
    /**
     * DigestWriterAssigned constructor.
     * @param string $paperId
     * @param int $sequence
     * @param string $insightAuthor
     * @param int $insightManuscriptNo
     * @param DateTime $insightDueDate
     * @param string $insightCommissionedComment
     */
    public function __construct(string $paperId, int $sequence, string $insightAuthor, string $insightManuscriptNo, \DateTime $insightDueDate, string $insightCommissionedComment)
    {
        parent::__construct($paperId, $sequence, [
            'insightAuthor' => $insightAuthor,
            'insightManuscriptNo' =>$insightManuscriptNo,
            'insightDueDate' => $insightDueDate,
            'insightCommissionedComment' => $insightCommissionedComment
        ]);
    }

    public function getInsightAuthor()
    {
        return $this->getFromPayload('insightAuthor');
    }

    public function getInsightManuscriptNo()
    {
        return $this->getFromPayload('insightManuscriptNo');
    }

    public function getInsightDueDate()
    {
        return $this->getDateTimeFromPayload('insightDueDate');
    }

    public function getInsightCommissionedComment()
    {
        return $this->getFromPayload('insightCommissionedComment');
    }
}