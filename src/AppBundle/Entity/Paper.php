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
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="string", length=100)
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


}