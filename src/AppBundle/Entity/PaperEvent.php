<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="paperEvent")
 */
class PaperEvent
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $paperId;

    /**
     * @ORM\Column(type="json_array")
     * @var mixed
     */
    private $payload;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    private $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return PaperEvent
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getPaperId()
    {
        return $this->paperId;
    }

    /**
     * @param int $paperId
     * @return PaperEvent
     */
    public function setPaperId($paperId)
    {
        $this->paperId = $paperId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     * @return PaperEvent
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PaperEvent
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}