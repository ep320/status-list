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
     * @ORM\Column(type="string", length=100)
     * @ORM\Id
     */
    private $code;

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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     */
    public function setPrimaryPapers($primaryPapers)
    {
        $this->primaryPapers = $primaryPapers;
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
     */
    public function setSecondaryPapers($secondaryPapers)
    {
        $this->secondaryPapers = $secondaryPapers;
    }

    public function getPapers()
    {
        return array_combine($this->getPrimaryPapers(), $this->getSecondaryPapers());
    }


    public function __toString()
    {
        return $this->getCode();
    }

}