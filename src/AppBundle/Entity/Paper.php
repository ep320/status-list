<?php
namespace AppBundle\Entity;

use AppDomain\Event\AnswersReceived;
use AppDomain\Event\NoDigestDecided;
use AppDomain\Event\AnswersReceivedUndone;
use AppDomain\Event\DigestSignedOff;
use AppDomain\Event\DigestWriterAssigned;
use AppDomain\Event\DigestReceived;
use AppDomain\Event\NoDigestDecidedUndone;
use AppDomain\Event\PaperEvent;
use Doctrine\ORM\EntityManager;
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
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $insightDecision;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $insightComment;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $noDigestStatus;


    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $answersStatus;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $answersInDigestForm;

    /**
     * @ORM\ManyToOne(targetEntity="DigestWriter", inversedBy="papersAssigned")
     * @ORM\JoinColumn(name="digest_written_by", referencedColumnName="id")
     * @Assert\Type(type="AppBundle\Entity\DigestWriter")
     * @Assert\Valid()
     */
    private $digestWrittenBy;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $digestDueDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $digestReceived;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $digestSignedOff;

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
    public function __construct($paperId, $manuscriptNo, $correspondingAuthor, $articleType,
                                $subjectArea1, $subjectArea2, $insightDecision, $insightComment)
    {
        $this->id = $paperId;
        $this->manuscriptNo = $manuscriptNo;
        $this->correspondingAuthor = $correspondingAuthor;
        $this->articleType = $articleType;
        $this->subjectArea1 = $subjectArea1;
        $this->subjectArea2 = $subjectArea2;
        $this->insightDecision = $insightDecision;
        $this->insightComment = $insightComment;
        $this->_version = 1;
        $this->answersInDigestForm = false;

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
    public function getInsightDecision()
    {
        return $this->insightDecision;
    }

    /**
     * @return mixed
     */
    public function getInsightComment()
    {
        return $this->insightComment;
    }


    /**
     * @return mixed
     */
    public function getNoDigestStatus()
    {
        return $this->noDigestStatus;
    }


    /**
     * @return mixed
     */
    public function getAnswersStatus()
    {
        return $this->answersStatus;
    }

    /**
     * @return mixed
     */
    public function getAnswersInDigestForm()
    {
        return $this->answersInDigestForm;
    }

    /**
     * @return mixed
     */
    public function getDigestWrittenBy()
    {
        return $this->digestWrittenBy;
    }

    /**
     * @return mixed
     */
    public function getDigestDueDate()
    {
        return $this->digestDueDate;
    }

    /**
     * @return mixed
     */
    public function getDigestReceived()
    {
        return $this->digestReceived;
    }

    /**
     * @return mixed
     */
    public function getDigestSignedOff()
    {
        return $this->digestSignedOff;
    }


    /**
     * Set the appropriate fields in response to an event. Eg, if the event is an instanceof 'InsightDecisionMade',
     * update the insight decision field on this snapshot. Also, always update the _version field to the event sequence
     * so that we know which events we've already processed.
     *
     * @param PaperEvent $event
     */
    public function applyEvent(PaperEvent $event, EntityManager $em)
    {
        if ($event instanceof NoDigestDecided) {
            $this->noDigestStatus = $event->getNoDigestReason();
        }
        if ($event instanceof NoDigestDecidedUndone) {
            $this->noDigestStatus = null;
        }
        if ($event instanceof AnswersReceived) {
            $this->answersStatus = $event->getAnswersQuality();
            $this->answersInDigestForm = $event->getIsInDigestForm();
        }
        if ($event instanceof AnswersReceivedUndone) {
            $this->answersStatus = null;
            $this->answersInDigestForm = false;
        }
        if ($event instanceof DigestWriterAssigned) {
            $this->digestWrittenBy = $em->getReference(DigestWriter::class, $event->getDigestWriterAssigned());
            $this->digestDueDate = $event->getDigestDueDate();
        }
        if ($event instanceof DigestReceived) {
            $this->digestReceived = $event->getDigestReceived();
        }
        if ($event instanceof DigestSignedOff) {
            $this->digestSignedOff = $event->getDigestSignedOff();
        }
        $this->_version = $event->getSequence();
    }
}