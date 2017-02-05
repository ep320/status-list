<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class InsightCommissionedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('insightCommissionedComment', TextType::class)
            ->add('insightDueDate', DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
            ))
            ->add('submitCommissioned', SubmitType::class);
    }
}

