<?php

namespace AppDomain;

use AppDomain\Aggregate\Paper;
use AppDomain\Command\AddPaperManually;
use AppDomain\Command\AssignDigestWriter;
use AppDomain\Command\AskInsightAuthor;
use AppDomain\Command\InsightAuthorChecking;
use AppDomain\Command\InsightAuthorRefuses;
use AppDomain\Command\MarkInsightAuthorReminded;
use AppDomain\Command\CommissionInsight;
use AppDomain\Command\AssignInsightEditor;
use AppDomain\Command\DecideToNotCommissionInsight;
use AppDomain\Command\MarkInsightSignedOff;
use AppDomain\Ejp\EjpPaper;
use AppDomain\Command\MarkAnswersReceived;
use AppDomain\Command\MarkDigestReceived;
use AppDomain\Command\MarkNoDigestDecided;
use AppDomain\Command\UndoAnswersReceived;
use AppDomain\Command\UndoNoDigestDecided;
use AppDomain\Ejp\EjpHasher;
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
use AppDomain\Event\AnswersReceived;
use AppDomain\Event\AnswersReceivedUndone;
use AppDomain\Event\DigestReceived;
use AppDomain\Event\DigestSignedOff;
use AppDomain\Event\DigestWriterAssigned;
use AppDomain\Event\EjpPaperImported;
use AppDomain\Event\NoDigestDecided;
use AppDomain\Event\NoDigestDecidedUndone;
use AppDomain\Event\PaperAdded;
use AppDomain\Event\PaperEvent;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;

class CommandHandler
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get the ID of a paper by its manuscript number, or null
     *
     * @param int $manuscriptNo
     * @return string | null
     */
    private function findIdByManuscriptNo(int $manuscriptNo)
    {
        /**
         * @var $result \PDOStatement
         */
        $result = $this->entityManager->getConnection()->executeQuery(
            'SELECT * FROM paper_event WHERE json_contains(payload, ?)',
            [json_encode(['manuscriptNo' => $manuscriptNo])]
        );

        $event = $result->fetch();

        if (!$event) {
            return null;
        }

        return $event['paper_id'];
    }

    /**
     * @param string $id
     * @return Paper
     */
    private function loadPaper(string $id)
    {
        $events = $this->entityManager->getRepository(PaperEvent::class)->findBy(['paperId' => $id], ['sequence' => 'ASC']);
        return Paper::load($events);
    }

    /**
     * Validate an AddPaperManually command, and publish PaperAdded on success
     *
     * @param AddPaperManually $command
     * @throws \Exception
     */
    public function addPaperManually(AddPaperManually $command)
    {

        if ($this->findIdByManuscriptNo($command->manuscriptNo)) {
            throw new \Exception('A paper with this manuscript no. is already in statusbase');
        }


        $subjectAreaIds = [$command->subjectAreaId1];
        if ($command->subjectAreaId2) {
            $subjectAreaIds[] = $command->subjectAreaId2;
        }

        $event = (new PaperAdded(
            $command->manuscriptNo,
            $command->correspondingAuthor,
            $command->articleTypeCode,
            $command->revision,
            $command->hadAppeal,
            $subjectAreaIds,
            'Manual',
            $command->insightDecision,
            $command->insightComment
        ));

        $this->publish($event);

    }

    /**
     * Validate an EjpPaper blob, and publish EjpPaperImported if anything has changed
     *
     * @param EjpPaper $ejpPaper
     */
    public function importFromEjp(EjpPaper $ejpPaper)
    {
        if ($paperId = $this->findIdByManuscriptNo($ejpPaper->getManuscriptNo())) {
            $existingPaper = $this->loadPaper($paperId);
            //We've seen this paper before. Publish an event only if it's different
            if ($existingPaper->getEjpHashForComparison() !== EjpHasher::RevisedPaperHash($ejpPaper)|| EjpHasher::AcceptedPaperHash($ejpPaper)) {

                //Update paper
                $nextVersion = $existingPaper->getVersion() + 1;

                $event = (new EjpPaperImported($paperId, $nextVersion, $ejpPaper));
                $this->publish($event);
                $nextVersion++;
                //Check to see if paper's been accepted
                if ($ejpPaper->isAccepted() === true && $existingPaper->isAccepted() === null) {
                    $this->publish(new PaperAcceptedEvent($paperId, $nextVersion, $ejpPaper));

                }


            }
            return;
        }

        //We haven't seen this paper before.
        $newPaperId = Uuid::uuid4();
        $event = (new EjpPaperImported($newPaperId, 1, $ejpPaper));
        $this->publish($event);
        //if new paper has also been accepted
        if ($ejpPaper->isAccepted() === true) {
            $this->publish(new PaperAcceptedEvent($newPaperId, 2, $ejpPaper));
        }
    }

    public function publish(PaperEvent $event)
    {
        $this->entityManager->persist($event);
        $this->entityManager->flush($event);
        return;
    }


    public
    function markNoDigestDecided(MarkNoDigestDecided $command)
    {

        $event = (new NoDigestDecided(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->noDigestReason
        ));

        $this->publish($event);

    }

    public
    function undoNoDigestDecided(UndoNoDigestDecided $command)
    {
        $event = (new NoDigestDecidedUndone($command->paperId, $this->getEventCount($command->paperId) + 1));
        $this->publish($event);
    }

    public
    function markAnswersReceived(MarkAnswersReceived $command)
    {

        $event = (new AnswersReceived(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->answersQuality,
            $command->isInDigestForm
        ));

        $this->publish($event);

    }

    public
    function undoAnswersReceived(UndoAnswersReceived $command)
    {
        $event = (new AnswersReceivedUndone($command->paperId, $this->getEventCount($command->paperId) + 1));
        $this->publish($event);
    }

    public
    function assignDigestWriter(AssignDigestWriter $command)
    {
        $event = (new DigestWriterAssigned(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->writerId,
            $command->dueDate
        ));
        $this->publish($event);
    }

    public
    function markDigestReceived(MarkDigestReceived $command)
    {
        $event = (new DigestReceived($command->paperId, $this->getEventCount($command->paperId) + 1, true));
        $this->publish($event);
    }

    public
    function markDigestSignedOff($paperId)
    {
        $event = (new DigestSignedOff($paperId, $this->getEventCount($paperId) + 1, true));
        $this->publish($event);
    }

    public
    function decideNotToCommissionInsight(DecideToNotCommissionInsight $command)
    {

        $event = (new NoInsightDecided(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->insightNotCommissionedReason
        ));

        $this->publish($event);

    }

    public
    function askInsightAuthor(AskInsightAuthor $command)
    {

        $event = (new InsightAuthorAsked(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->insightAuthor,
            $command->insightDueDate
        ));

        $this->publish($event);

    }

    public
    function insightAuthorRefuses(InsightAuthorRefuses $command)
    {

        $event = (new InsightAuthorRefused(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->insightAuthorRefusalReason
        ));

        $this->publish($event);

    }

    public
    function commissionInsight(CommissionInsight $command)
    {

        $event = (new InsightCommissioned(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->insightManuscriptNo,
            $command->insightDueDate,
            $command->insightCommissionedComment

        ));

        $this->publish($event);

    }

    public function remindInsightAuthor($paperId)
    {
        $event = (new InsightAuthorReminded(
            $paperId,
            $this->getEventCount($paperId) + 1
        ));

        $this->publish($event);
    }


    public function acknowledgeInsight($paperId)
    {
        $event = (new InsightAcknowledged(
            $paperId,
            $this->getEventCount($paperId) + 1
        ));

        $this->publish($event);
    }

    public
    function assignInsightEditor(AssignInsightEditor $command)
    {
        $event = (new InsightEditorAssigned(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->insightEditor
        ));
        $this->publish($event);
    }

    public function insightAuthorChecking(InsightAuthorChecking $command)
    {
        $event = (new InsightToAuthorSent(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->insightEditsDueDate
        ));

        $this->publish($event);
    }

    public function signOffInsight($paperId)
    {
        $event = (new InsightSignedOff(
            $paperId,
            $this->getEventCount($paperId) + 1
        ));

        $this->publish($event);
    }




    private
    function getEventCount(string $paperId)
    {
        $result = $this->entityManager->createQuery(
            'SELECT COUNT(e.sequence) FROM AppDomain:PaperEvent e WHERE e.paperId = :paperId'
        );
        $result->setParameter('paperId', $paperId);
        $count = $result->getSingleScalarResult();

        return $count;
    }


}

