<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\SubjectArea;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSubjectAreaData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $manager->persist((new SubjectArea())->setId(15)->setDescription('Biochemistry'));
        $manager->persist((new SubjectArea())->setId(16)->setDescription('Biophysics and structural biology'));
        $manager->persist((new SubjectArea())->setId(17)->setDescription('Cell biology'));
        $manager->persist((new SubjectArea())->setId(18)->setDescription('Developmental biology and stem cells'));
        $manager->persist((new SubjectArea())->setId(19)->setDescription('Genes and chromosomes'));
        $manager->persist((new SubjectArea())->setId(20)->setDescription('Genomics and evolutionary biology'));
        $manager->persist((new SubjectArea())->setId(21)->setDescription('Human biology and medicine'));
        $manager->persist((new SubjectArea())->setId(22)->setDescription('Immunology'));
        $manager->persist((new SubjectArea())->setId(23)->setDescription('Microbiology and infectious disease'));
        $manager->persist((new SubjectArea())->setId(24)->setDescription('Neuroscience'));
        $manager->persist((new SubjectArea())->setId(25)->setDescription('Plant biology'));
        $manager->persist((new SubjectArea())->setId(63)->setDescription('Ecology'));
        $manager->persist((new SubjectArea())->setId(64)->setDescription('Epidemiology and global health'));
        $manager->persist((new SubjectArea())->setId(65)->setDescription('Computational systems biology'));
        $manager->persist((new SubjectArea())->setId(78)->setDescription('Cancer biology'));

        $manager->flush();
    }
}