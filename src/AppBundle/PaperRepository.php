<?php
namespace AppBundle;

use AppBundle\Entity\ArticleType;
use AppBundle\Entity\Paper;
use AppBundle\Entity\SubjectArea;
use AppDomain\Event\PaperAdded;
use Doctrine\ORM\EntityRepository;

class PaperRepository extends EntityRepository
{
    /**
     * Set up a new Paper instance in response to a PaperAdded event
     *
     * @param PaperAdded $paperAdded
     * @return Paper
     * @throws \Doctrine\ORM\ORMException
     */
    public function handlePaperAdded(PaperAdded $paperAdded) {
        $em = $this->getEntityManager();

        $articleType = $em->getReference(ArticleType::class, $paperAdded->getArticleTypeCode());
        $subjectArea1 = $subjectArea2 = null;
        $subjectAreaIds = $paperAdded->getSubjectAreaIds();
        if (isset($subjectAreaIds[0])) {
            $subjectArea1 = $em->getReference(SubjectArea::class, $subjectAreaIds[0]);
        }
        if (isset($subjectAreaIds[1])) {
            $subjectArea2 = $em->getReference(SubjectArea::class, $subjectAreaIds[1]);
        }

        $paper = new Paper(
            $paperAdded->getPaperId(),
            $paperAdded->getManuscriptNo(),
            $paperAdded->getTime(),
            $paperAdded->getCorrespondingAuthor(),
            $articleType,
            $subjectArea1,
            $subjectArea2,
            $paperAdded->getInsightDecision(),
            $paperAdded->getInsightComment()
        );

        $this->getEntityManager()->persist($paper);
        return $paper;
    }
}