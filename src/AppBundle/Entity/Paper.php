<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=3)
     */
    private $articleType;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $subjectArea1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
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