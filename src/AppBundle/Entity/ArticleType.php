<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class ArticleType
{
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=3)
     * @ORM\Id
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="Paper", mappedBy="articleType")
     */
    private $papers;

    public function __construct()
    {
        $this->papers = new ArrayCollection();
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
     * @return ArticleType
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPapers()
    {
        return $this->papers;
    }

    /**
     * @param mixed $papers
     * @return ArticleType
     */
    public function setPapers($papers)
    {
        $this->papers = $papers;
        return $this;
    }

    public function __toString()
    {
        return $this->getCode();
    }


}