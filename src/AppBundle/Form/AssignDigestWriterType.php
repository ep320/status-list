<?php
namespace AppBundle\Form;


use AppBundle\Entity\DigestWriter;
use AppBundle\Form\DataTransformer\IdToEntityTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AssignDigestWriterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('writerId', EntityType::class, array(
                'class' => DigestWriter::class,
                'choice_label' => 'name'
            ))
            ->add('dueDate', DateType::class, [
                // render as a single text box
                'widget' => 'single_text'
            ])
            ->add('save digest info', SubmitType::class);

        $builder->get('writerId')->addModelTransformer(new IdToEntityTransformer($options['em'], DigestWriter::class));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['em']);
    }
}

