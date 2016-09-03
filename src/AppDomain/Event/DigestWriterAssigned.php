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
     * @param string $writerId
     * @param DateTime $digestDueDate
     */
    public function __construct(string $paperId, int $sequence, string $writerId, \DateTime $digestDueDate)
    {
        parent::__construct($paperId, $sequence, [
            'writerId' => $writerId,
            'digestDueDate' => $digestDueDate,
        ]);
    }

    public function getDigestWriterAssigned()
    {
        return $this->getFromPayload('writerId');
    }

    public function getDigestDueDate()
    {
        return $this->getDateTimeFromPayload('digestDueDate');
    }
}