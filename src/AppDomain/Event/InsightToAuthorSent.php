<?php


namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * @ORM\Entity
 */
class InsightToAuthorSent extends PaperEvent
{
    /**
     * DigestWriterAssigned constructor.
     * @param string $paperId
     * @param int $sequence
     * @param bool $insightAuthorChecking
     * @param DateTime $insightEditsDueDate
     */
    public function __construct(string $paperId, int $sequence, bool $insightAuthorChecking, \DateTime $insightEditsDueDate)
    {
        parent::__construct($paperId, $sequence, [
            'insightAuthorChecking' => $insightAuthorChecking,
            'insightEditsDueDate' => $insightEditsDueDate
        ]);
    }


    public function getInsightEditsDueDate()
    {
        return $this->getDateTimeFromPayload('insightEditsDueDate');
    }


}