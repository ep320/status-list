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
     * @param DateTime $insightEditsDueDate
     */
    public function __construct(string $paperId, int $sequence, \DateTime $insightEditsDueDate)
    {
        parent::__construct($paperId, $sequence, [
            'insightEditsDueDate' => $insightEditsDueDate,
        ]);
    }


    public function getInsightEditsDueDate()
    {
        return $this->getDateTimeFromPayload('insightEditsDueDate');
    }


}