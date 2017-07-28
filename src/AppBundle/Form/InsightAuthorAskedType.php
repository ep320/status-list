<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class InsightAuthorAskedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('insightCommissioningReason', TextType::class, array(
                'label' => 'Commissioning Reason (optional)',
                'required' => false))
            ->add('insightAuthor', TextType::class, array('label' => 'Author to be asked'))
            ->add('submit', SubmitType::class);
    }
}

