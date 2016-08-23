<?php

namespace AppDomain;

use AppDomain\Command\AddPaperManually;
use AppDomain\Command\AssignDigestWriter;
use AppDomain\Command\ImportPaperDetails;
use AppDomain\Command\MarkAnswersReceived;
use AppDomain\Command\MarkNoDigestDecided;
use AppDomain\Event\AnswersReceived;
use AppDomain\Event\AnswersReceivedUndone;
use AppDomain\Event\DigestReceived;
use AppDomain\Event\DigestSignedOff;
use AppDomain\Event\DigestWriterAssigned;
use AppDomain\Event\NoDigestDecided;
use AppDomain\Event\NoDigestDecidedUndone;
use AppDomain\Event\PaperAdded;
use AppDomain\Event\PaperEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

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
     * Check whether manuscript no of imported paper matches a manuscript no already in statusbase
     *
     * @param int $manuscriptNo
     * @return bool
     */
    private function doesPaperExist(int $manuscriptNo)
    {
        /**
         * @var $result \PDOStatement
         */
        $result = $this->entityManager->getConnection()->executeQuery(
            'SELECT * FROM paper_event WHERE json_contains(payload, ?)',
            [json_encode(['manuscriptNo' => $manuscriptNo])]
        );

        return $result->fetch() !== false;
    }

    /**
     * Validate an AddPaperManually command, and publish PaperAdded on success
     *
     * @param AddPaperManually $command
     */
    public function addPaperManually(AddPaperManually $command)
    {

        if ($this->doesPaperExist($command->manuscriptNo)) {
            throw new \Exception('A paper with this manuscript no. is already in statusbase');
        }


        $subjectAreas = [$command->subjectArea1];
        if ($command->subjectArea2) {
            $subjectAreas[] = $command->subjectArea2;
        }

        $event = (new PaperAdded(
            $command->manuscriptNo,
            $command->correspondingAuthor,
            $command->articleType,
            $subjectAreas,
            'Manual'
        ));

        $this->publish($event);

    }

    /**
     * Validate an ImportPaperDetails command, and publish PaperAdded on success
     *
     * @param ImportPaperDetails $command
     */
    public function importPaperDetails(ImportPaperDetails $command)
    {
        if ($this->doesPaperExist($command->manuscriptNo)) {
            return;
        }

        $subjectAreas = [$command->subjectArea1];
        if ($command->subjectArea2) {
            $subjectAreas[] = $command->subjectArea2;
        }

        $event = (new PaperAdded(
            $command->manuscriptNo,
            $command->correspondingAuthor,
            $command->articleType,
            $subjectAreas,
            'Imported'
        ));

        $this->publish($event);

    }

    public function publish(PaperEvent $event)
    {
        $this->entityManager->persist($event);
        $this->entityManager->flush($event);
        return;
    }

    public function markNoDigestDecided(MarkNoDigestDecided $command)
    {

        $event = (new NoDigestDecided(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->noDigestReason
        ));

        $this->publish($event);

    }

    public function undoNoDigestDecided(string $paperId)
    {
        $event = (new NoDigestDecidedUndone($paperId, $this->getEventCount($paperId) + 1));
        $this->publish($event);
    }

    public function markAnswersReceived(MarkAnswersReceived $command)
    {

        $event = (new AnswersReceived(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->answersQuality,
            $command->isInDigestForm
        ));

        $this->publish($event);

    }

    public function undoAnswersReceived(string $paperId)
    {
        $event = (new AnswersReceivedUndone($paperId, $this->getEventCount($paperId) + 1));
        $this->publish($event);
    }

    public function assignDigestWriter(AssignDigestWriter $command)
    {
        $event = (new DigestWriterAssigned(
            $command->paperId,
            $this->getEventCount($command->paperId) + 1,
            $command->writer->getId(),
            $command->dueDate
        ));
        $this->publish($event);
    }

    public function markDigestReceived($paperId)
    {
        $event = (new DigestReceived($paperId, $this->getEventCount($paperId) + 1, true));
        $this->publish($event);
    }

    public function markDigestSignedOff($paperId)
    {
        $event = (new DigestSignedOff($paperId, $this->getEventCount($paperId) + 1, true));
        $this->publish($event);
    }

    private function getEventCount(string $paperId)
    {
        $result = $this->entityManager->createQuery(
            'SELECT COUNT(e.sequence) FROM AppDomain:PaperEvent e WHERE e.paperId = :paperId'
        );
        $result->setParameter('paperId', $paperId);
        $count = $result->getSingleScalarResult();

        return $count;
    }


}

