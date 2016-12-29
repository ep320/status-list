<?php

namespace AppDomain\Event;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="name", type="string")
 * @ORM\DiscriminatorMap({
 *     "DigestWriterAssigned" = "DigestWriterAssigned",
 *     "PaperAdded" = "PaperAdded",
 *     "EjpPaperImported" = "EjpPaperImported",
 *     "NoDigestDecided" = "NoDigestDecided",
 *     "NoDigestDecidedUndone" = "NoDigestDecidedUndone",
 *     "AnswersReceived" = "AnswersReceived",
 *     "AnswersReceivedUndone" = "AnswersReceivedUndone",
 *     "DigestReceived" = "DigestReceived",
 *     "DigestSignedOff" = "DigestSignedOff",
 *     "PaperAcceptedEvent" = "PaperAcceptedEvent"
 * })
 */
abstract class PaperEvent
{
    /**
     * @ORM\Column(type="string", length=100)
     * @ORM\Id
     * @var string
     */
    private $paperId;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @var int
     */
    private $sequence;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     * @var mixed
     */
    private $payload;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $time;

    public function __construct($paperId, $sequence, $payload = null)
    {
        $this->paperId = $paperId;
        $this->sequence = $sequence;
        $this->payload = $payload;
        $this->time = new \DateTime();
    }

    /**
     * @return string
     */
    public function getPaperId()
    {
        return $this->paperId;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getFromPayload(string $key, $default = null)
    {
        if ($this->payload && isset($this->payload[$key])) {
            return $this->payload[$key];
        }
        return $default;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return \DateTime
     */
    protected function getDateTimeFromPayload(string $key, $default = null)
    {
        if ($this->payload && isset($this->payload[$key]) && ($rawDate = $this->payload[$key])) {
            if ($rawDate instanceof \DateTime) {
                return $rawDate;
            }
            if (is_array($rawDate)) {
                return \DateTime::createFromFormat('Y-m-d H:i:s.u', $rawDate['date'], new \DateTimeZone($rawDate['timezone']));
            }
        }
        return $default;
    }

    /**
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }
}