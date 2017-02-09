<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class InsightAuthorCheckingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('insightEditsDueDate', DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
                'label' => 'Edits due date'
            ))
            ->add('submitChecking', SubmitType::class, array('label' => 'Author checking'));
    }
}

