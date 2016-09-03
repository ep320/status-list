<?php
namespace AppBundle\Form;

use AppBundle\Entity\ArticleType;
use AppBundle\Entity\SubjectArea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class AddPaperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('manuscriptNo')
            ->add('correspondingAuthor')
            ->add('articleType', EntityType::class, array(
                'class' => ArticleType::class,
                'choice_label' => 'code',
                'preferred_choices' => function (ArticleType $val) {
                    return ($val == 'RA');
                }
            ))
            ->add('revision', ChoiceType::class, array(
                'choices' => [1 => '1', 2 => '2', 3 => '3', 4 => '4']
            ))
            ->add('hadAppeal', CheckboxType::class, array(
                'label' => 'Appeal?',
                'required' => false))
            ->
            add('subjectArea1', EntityType::class, array(
                'class' => SubjectArea::class,
                'choice_label' => 'description'
            ))
            ->add('subjectArea2', EntityType::class, array(
                'class' => SubjectArea::class,
                'choice_label' => 'description'
            ))
            ->add('insightDecision', ChoiceType::class, array(
                'choices' => ['yes' => 'Yes', 'no' => 'No']))
            ->add('insightComment')
            ->add('save', SubmitType::class);
    }
}

