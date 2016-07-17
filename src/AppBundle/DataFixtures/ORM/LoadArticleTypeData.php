<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ArticleType;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadArticleTypeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $manager->persist((new ArticleType())->setCode('ADV'));
        $manager->persist((new ArticleType())->setCode('RE'));
        $manager->persist((new ArticleType())->setCode('RA'));
        $manager->persist((new ArticleType())->setCode('TR'));
        $manager->persist((new ArticleType())->setCode('SR'));
        $manager->flush();
    }
}