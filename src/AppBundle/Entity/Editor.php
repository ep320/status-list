<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Editor
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
     * @ORM\OneToMany(targetEntity="Paper", mappedBy="insightEditor")
     */
    private $insightsAssigned;

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
     * @return mixed
     */
    public function getInsightsAssigned()
    {
        return $this->insightsAssigned;
    }



    /**
     * @param mixed $id
     * @return Editor
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $name
     * @return Editor
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $papersAssigned
     * @return Editor
     */
    public function setPapersAssigned($papersAssigned)
    {
        $this->papersAssigned = $papersAssigned;
        return $this;
    }

    /**
     * @param mixed $insightsAssigned
     * @return Editor
     */
    public function setInsightsAssigned($insightsAssigned)
    {
        $this->insightsAssigned = $insightsAssigned;
    }



    public function __toString()
    {
        return $this->getName();
    }


}