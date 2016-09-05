<?php
namespace AppBundle;

use AppBundle\Entity\ArticleType;
use AppBundle\Entity\Paper;
use AppBundle\Entity\SubjectArea;
use AppDomain\Event\PaperAdded;
use Doctrine\ORM\EntityRepository;

class PaperRepository extends EntityRepository
{
}
