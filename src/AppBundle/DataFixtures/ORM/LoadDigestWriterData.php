<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\DigestWriter;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDigestWriterData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $manager->persist((new DigestWriter())->setName('Jane I'));
        $manager->persist((new DigestWriter())->setName('Bridget'));
        $manager->persist((new DigestWriter())->setName('Deepa'));
        $manager->persist((new DigestWriter())->setName('Charvy'));
        $manager->persist((new DigestWriter())->setName('Victoria'));
        $manager->persist((new DigestWriter())->setName('Features team'));

        $manager->flush();
    }
}