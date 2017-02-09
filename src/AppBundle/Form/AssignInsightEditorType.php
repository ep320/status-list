<?php
namespace AppBundle\Form;


use AppBundle\Entity\DigestWriter;
use AppBundle\Entity\Editor;
use AppBundle\Form\DataTransformer\IdToEntityTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AssignInsightEditorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('insightEditor', EntityType::class, array(
                'class' => Editor::class,
                'choice_label' => 'name'
            ))

            ->add('assignEditor', SubmitType::class);

        $builder->get('insightEditor')->addModelTransformer(new IdToEntityTransformer($options['em'], Editor::class));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['em']);
    }
}

