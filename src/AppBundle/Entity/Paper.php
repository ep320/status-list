<?php
namespace AppBundle\Entity;

use AppDomain\Command\InsightAuthorChecking;
use AppDomain\Event\AnswersReceived;
use AppDomain\Event\EjpPaperImported;
use AppDomain\Event\InsightAcknowledged;
use AppDomain\Event\InsightAuthorAsked;
use AppDomain\Event\InsightAuthorRefused;
use AppDomain\Event\InsightAuthorReminded;
use AppDomain\Event\InsightCommissioned;
use AppDomain\Event\InsightEditorAssigned;
use AppDomain\Event\InsightSignedOff;
use AppDomain\Event\InsightToAuthorSent;
use AppDomain\Event\NoInsightDecided;
use AppDomain\Event\PaperAcceptedEvent;
use AppDomain\Event\NoDigestDecided;
use AppDomain\Event\AnswersReceivedUndone;
use AppDomain\Event\DigestSignedOff;
use AppDomain\Event\DigestWriterAssigned;
use AppDomain\Event\DigestReceived;
use AppDomain\Event\NoDigestDecidedUndone;
use AppDomain\Event\PaperAdded;
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAdded;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $revision;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hadAppeal;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $impactStatement;

    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    private $abstract;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $insightDecision;

    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    private $insightComment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $insightUpdatedDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accepted = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $acceptedDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $digestQuestionsAsked;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $digestAnswersGiven;

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
    private $answersInDigestForm = false;

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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $insightCommissioned;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $activeInsightAuthor;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     * @var mixed
     */
    private $askedInsightAuthors;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $insightCommissioningDecisionComment;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $insightAuthorRefusalReason;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $insightAuthorReminded = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $insightAuthorAcknowledged = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $insightAuthorChecking = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $insightEditor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $insightManuscriptNo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $insightDueDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $insightEditsDueDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $insightSignedOff = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $_version;

    public function __construct(string $id)
    {
        $this->id = $id;
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
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
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
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @return mixed
     */
    public function getHadAppeal()
    {
        return $this->hadAppeal;
    }

    /**
     * @return mixed
     */
    public function getImpactStatement()
    {
        return $this->impactStatement;
    }

    /**
     * @return mixed
     */
    public function getAbstract()
    {
        return $this->abstract;
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
    public function getInsightUpdatedDate()
    {
        return $this->insightUpdatedDate;
    }

    /**
     * @return mixed
     */
    public function getAccepted()
    {
        return $this->accepted;
    }


    /**
     * @return mixed
     */
    public function getAcceptedDate()
    {
        return $this->acceptedDate;
    }

    /**
     * @return mixed
     */
    public function getDigestQuestionsAsked()
    {
        return $this->digestQuestionsAsked;
    }



    /**
     * @return mixed
     */
    public function getDigestAnswersGiven()
    {
        return $this->digestAnswersGiven;
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
     * @return mixed
     */
    public function getInsightCommissioned()
    {
        return $this->insightCommissioned;
    }

    /**
     * @return mixed
     */
    public function getActiveInsightAuthor()
    {
        return $this->activeInsightAuthor;
    }

    /**
     * @return mixed
     */
    public function getAskedInsightAuthors()
    {
        return $this->askedInsightAuthors;
    }


    /**
     * @return mixed
     */
    public function getInsightCommissioningDecisionComment()
    {
        return $this->insightCommissioningDecisionComment;
    }

    /**
     * @return mixed
     */
    public function getInsightAuthorRefusalReason()
    {
        return $this->insightAuthorRefusalReason;
    }

    /**
     * @return mixed
     */
    public function getInsightAuthorReminded()
    {
        return $this->insightAuthorReminded;
    }

    /**
     * @return mixed
     */
    public function getInsightAuthorAcknowledged()
    {
        return $this->insightAuthorAcknowledged;
    }

    /**
     * @return mixed
     */
    public function getInsightAuthorChecking()
    {
        return $this->insightAuthorChecking;
    }

    /**
     * @return mixed
     */
    public function getInsightSignedOff()
    {
        return $this->insightSignedOff;
    }



    /**
     * @return mixed
     */
    public function getInsightEditor()
    {
        return $this->insightEditor;
    }

    /**
     * @return mixed
     */
    public function getInsightManuscriptNo()
    {
        return $this->insightManuscriptNo;
    }

    /**
     * @return mixed
     */
    public function getInsightDueDate()
    {
        return $this->insightDueDate;
    }

    /**
     * @return mixed
     */
    public function getInsightEditsDueDate()
    {
        return $this->insightEditsDueDate;
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
        if ($event->getSequence() === 1) {
            $this->dateAdded = $event->getTime();
            $this->insightUpdatedDate = $event->getTime();
        }
        if ($event instanceof PaperAdded || $event instanceof EjpPaperImported) {
            $articleType = $em->getReference(ArticleType::class, $event->getArticleTypeCode());
            $subjectArea1 = $subjectArea2 = null;
            $subjectAreaIds = $event->getSubjectAreaIds();
            if (isset($subjectAreaIds[0])) {
                $subjectArea1 = $em->getReference(SubjectArea::class, $subjectAreaIds[0]);
            }
            if (isset($subjectAreaIds[1])) {
                $subjectArea2 = $em->getReference(SubjectArea::class, $subjectAreaIds[1]);
            }

            $this->manuscriptNo = $event->getManuscriptNo();
            $this->correspondingAuthor = $event->getCorrespondingAuthor();
            $this->articleType = $articleType;
            $this->revision = $event->getRevision();
            $this->hadAppeal = $event->getHadAppeal();
            $this->subjectArea1 = $subjectArea1;
            $this->subjectArea2 = $subjectArea2;
            if ($event instanceof EjpPaperImported) {
                if ($this->insightDecision !== $event->getInsightDecision() ||
                    $this->insightComment !== $event->getInsightComment()
                ) {
                    $this->insightUpdatedDate = $event->getTime();
                }
            }
            $this->insightDecision = $event->getInsightDecision();
            $this->insightComment = $event->getInsightComment();
        }

        if ($event instanceof PaperAcceptedEvent) {
            $this->accepted = true;
            $this->acceptedDate = $event->getAcceptedDate();
            $this->digestQuestionsAsked = $event->getDigestQuestionsAsked();
            $this->digestAnswersGiven = $event->getDigestAnswersGiven();
            $this->impactStatement = $event->getImpactStatement();
            $this->abstract = $event->getAbstract();
            if ($this->insightDecision == 'No'){
                $this->insightCommissioned = false;
            }
            else{
                $this->insightCommissioned = null;
            }
        }
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
        if ($event instanceof InsightCommissioned){
            $this->insightCommissioned = true;
            $this->insightDueDate = $event->getInsightDueDate();
            $this->insightManuscriptNo = $event->getInsightManuscriptNo();
            $this->insightCommissioningDecisionComment = $event->getInsightCommissionedComment();
        }
        if ($event instanceof NoInsightDecided){
            $this->insightCommissioned = false;
            $this->insightCommissioningDecisionComment = $event->getinsightNotCommissionedReason();
        }
        if ($event instanceof InsightAuthorAsked){
            $this->activeInsightAuthor = $event->getActiveInsightAuthor();
            $this->askedInsightAuthors = $event->getAskedInsightAuthors();
        }
        if ($event instanceof InsightAuthorRefused){
            $this->insightAuthorRefusalReason = $event->getinsightAuthorRefusalReason();
            $this->activeInsightAuthor = null;
        }
        if ($event instanceof InsightAuthorReminded){
            $this->insightAuthorReminded = true;
        }
        if ($event instanceof InsightAcknowledged){
            $this->insightAuthorAcknowledged = true;
        }
        if ($event instanceof InsightEditorAssigned){
            $this->insightEditor = $event->getInsightEditorAssigned();
        }
        if ($event instanceof InsightToAuthorSent){
            $this->insightAuthorChecking = true;
            $this->insightEditsDueDate = $event->getInsightEditsDueDate();
        }
        if ($event instanceof InsightSignedOff){
            $this->insightSignedOff = true;
        }


        $this->_version = $event->getSequence();
    }
}