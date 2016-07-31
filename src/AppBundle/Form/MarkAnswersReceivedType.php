<?php
namespace AppBundle\Form;

use AppBundle\Entity\ArticleType;
use AppBundle\Entity\SubjectArea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class MarkAnswersReceivedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answersQuality')
            ->add('save', SubmitType::class);
    }
}

