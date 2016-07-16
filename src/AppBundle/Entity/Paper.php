<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="paper")
 */
class Paper
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $manuscriptNo;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $correspondingAuthor;

    /**
     * @ORM\ManyToOne(targetEntity="ArticleType", inversedBy="papers")
     * @ORM\JoinColumn(name="article_type", referencedColumnName="code", nullable=false)
     * @Assert\Type(type="AppBundle\Entity\ArticleType")
     * @Assert\Valid()
     */
    private $articleType;

    /**
     * @ORM\ManyToOne(targetEntity="SubjectArea", inversedBy="primaryPapers")
     * @ORM\JoinColumn(name="subject_area1", referencedColumnName="code", nullable=false)
     * @Assert\Type(type="AppBundle\Entity\SubjectArea")
     * @Assert\Valid()
     */
    private $subjectArea1;

    /**
     * @ORM\ManyToOne(targetEntity="SubjectArea", inversedBy="secondaryPapers")
     * @ORM\JoinColumn(name="subject_area2", referencedColumnName="code")
     * @Assert\Type(type="AppBundle\Entity\SubjectArea")
     * @Assert\Valid()
     */
    private $subjectArea2;

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
    public function getManuscriptNo()
    {
        return $this->manuscriptNo;
    }

    /**
     * @return mixed
     */
    public function getCorrespondingAuthor()
    {
        return $this->correspondingAuthor;
    }

    /**
     * @return mixed
     */
    public function getArticleType()
    {
        return $this->articleType;
    }

    /**
     * @return mixed
     */
    public function getSubjectArea1()
    {
        return $this->subjectArea1;
    }

    /**
     * @return mixed
     */
    public function getSubjectArea2()
    {
        return $this->subjectArea2;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $manuscriptNo
     */
    public function setManuscriptNo($manuscriptNo)
    {
        $this->manuscriptNo = $manuscriptNo;
    }

    /**
     * @param mixed $correspondingAuthor
     */
    public function setCorrespondingAuthor($correspondingAuthor)
    {
        $this->correspondingAuthor = $correspondingAuthor;
    }

    /**
     * @param mixed $articleType
     */
    public function setArticleType($articleType)
    {
        $this->articleType = $articleType;
    }

    /**
     * @param mixed $subjectArea1
     */
    public function setSubjectArea1($subjectArea1)
    {
        $this->subjectArea1 = $subjectArea1;
    }

    /**
     * @param mixed $subjectArea2
     */
    public function setSubjectArea2($subjectArea2)
    {
        $this->subjectArea2 = $subjectArea2;
    }


}