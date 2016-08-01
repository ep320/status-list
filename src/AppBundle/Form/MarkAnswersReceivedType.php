<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class MarkAnswersReceivedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answersQuality', ChoiceType::class, array(
                    'choices' => ['Good' => 'Good', 'Technical' => 'Technical']
                )
            )
            ->add('isInDigestForm', CheckboxType::class, array(
                'label' =>'Author wrote digest?',
                'required' => false
                ))
            ->add('save', SubmitType::class);
    }
}

