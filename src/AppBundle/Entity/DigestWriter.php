<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table()
 */
class DigestWriter
{
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Id
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=40)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Paper", mappedBy="digestWrittenBy")
     */
    private $papersAssigned;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPapersAssigned()
    {
        return $this->papersAssigned;
    }

    /**
     * @param mixed $id
     * @return DigestWriter
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $name
     * @return DigestWriter
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $papersAssigned
     * @return DigestWriter
     */
    public function setPapersAssigned($papersAssigned)
    {
        $this->papersAssigned = $papersAssigned;
        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }


}