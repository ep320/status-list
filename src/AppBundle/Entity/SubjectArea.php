<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class SubjectArea
{
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=100)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Paper", mappedBy="subjectArea1")
     */
    private $primaryPapers;

    /**
     * @ORM\OneToMany(targetEntity="Paper", mappedBy="subjectArea2")
     */
    private $secondaryPapers;

    public function __construct()
    {
        $this->primaryPapers = new ArrayCollection();
        $this->secondaryPapers = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return SubjectArea
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return SubjectArea
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimaryPapers()
    {
        return $this->primaryPapers;
    }

    /**
     * @param mixed $primaryPapers
     * @return SubjectArea
     */
    public function setPrimaryPapers($primaryPapers)
    {
        $this->primaryPapers = $primaryPapers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecondaryPapers()
    {
        return $this->secondaryPapers;
    }

    /**
     * @param mixed $secondaryPapers
     * @return SubjectArea
     */
    public function setSecondaryPapers($secondaryPapers)
    {
        $this->secondaryPapers = $secondaryPapers;
        return $this;
    }


    public function getPapers()
    {
        return array_combine($this->getPrimaryPapers(), $this->getSecondaryPapers());
    }


    public function __toString()
    {
        return $this->getDescription();
    }

}