<?php
namespace AppBundle\Form;

use AppBundle\Entity\ArticleType;
use AppBundle\Entity\SubjectArea;
use AppBundle\Form\DataTransformer\IdToEntityTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AddPaperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('manuscriptNo')
            ->add('correspondingAuthor')
            ->add('articleTypeCode', EntityType::class, array(
                'class' => ArticleType::class,
                'choice_label' => 'code',
                'preferred_choices' => function (ArticleType $val) {
                    return ($val == 'RA');
                },
                'label' => 'Article type'
            ))
            ->add('revision', ChoiceType::class, array(
                'choices' => [1 => '1', 2 => '2', 3 => '3', 4 => '4']
            ))
            ->add('hadAppeal', CheckboxType::class, array(
                'label' => 'Appeal?',
                'required' => false))
            ->
            add('subjectAreaId1', EntityType::class, array(
                'class' => SubjectArea::class,
                'choice_label' => 'description',
                'label' => 'Subject area 1'
            ))
            ->add('subjectAreaId2', EntityType::class, array(
                'class' => SubjectArea::class,
                'choice_label' => 'description',
                'label' => 'Subject area 2'
            ))
            ->add('insightDecision', ChoiceType::class, array(
                'choices' => ['yes' => 'Yes', 'no' => 'No']))
            ->add('insightComment')
            ->add('save', SubmitType::class);

        $builder->get('articleTypeCode')->addModelTransformer(new IdToEntityTransformer($options['em'], ArticleType::class));
        $builder->get('subjectAreaId1')->addModelTransformer(new IdToEntityTransformer($options['em'], SubjectArea::class));
        $builder->get('subjectAreaId2')->addModelTransformer(new IdToEntityTransformer($options['em'], SubjectArea::class));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['em']);
    }
}
