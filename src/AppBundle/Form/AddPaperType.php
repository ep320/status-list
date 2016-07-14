<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AddPaperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('manuscriptNo')
            ->add('correspondingAuthor')
            ->add('articleType')
            ->add('subjectArea1')
            ->add('subjectArea2')
            ->add('save', SubmitType::class)
        ;
    }
}

