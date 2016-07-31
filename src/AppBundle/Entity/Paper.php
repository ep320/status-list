<?php
namespace AppBundle\Entity;

use AppDomain\Event\AnswersReceived;
use AppDomain\Event\PaperEvent;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="AppBundle\PaperRepository")
 * @ORM\Table(name="paper")
 */
class Paper
{
    /**
     * @ORM\Column(type="string", length=100)
     * @ORM\Id
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
     * @ORM\JoinColumn(name="subject_area1", referencedColumnName="id", nullable=false)
     * @Assert\Type(type="AppBundle\Entity\SubjectArea")
     * @Assert\Valid()
     */
    private $subjectArea1;

    /**
     * @ORM\ManyToOne(targetEntity="SubjectArea", inversedBy="secondaryPapers")
     * @ORM\JoinColumn(name="subject_area2", referencedColumnName="id")
     * @Assert\Type(type="AppBundle\Entity\SubjectArea")
     * @Assert\Valid()
     */
    private $subjectArea2;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $answersStatus;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $_version;

    /**
     * @param $paperId
     * @param $manuscriptNo
     * @param $correspondingAuthor
     * @param $articleType
     * @param $subjectArea1
     * @param $subjectArea2
     */
    public function __construct($paperId, $manuscriptNo, $correspondingAuthor, $articleType, $subjectArea1, $subjectArea2) {
        $this->id = $paperId;
        $this->manuscriptNo = $manuscriptNo;
        $this->correspondingAuthor = $correspondingAuthor;
        $this->articleType = $articleType;
        $this->subjectArea1 = $subjectArea1;
        $this->subjectArea2 = $subjectArea2;
        $this->_version = 1;
    }

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
     * @return mixed
     */
    public function getAnswersStatus()
    {
        return $this->answersStatus;
    }



    /**
     * Set the appropriate fields in response to an event. Eg, if the event is an instanceof 'InsightDecisionMade',
     * update the insight decision field on this snapshot. Also, always update the _version field to the event sequence
     * so that we know which events we've already processed.
     *
     * @param PaperEvent $event
     */
    public function applyEvent(PaperEvent $event)
    {
        if($event instanceof AnswersReceived){
            $this->answersStatus = $event->getAnswersQuality();
        }
        $this->_version = $event->getSequence();
    }
}