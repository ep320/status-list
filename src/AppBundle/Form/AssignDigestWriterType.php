<?php
namespace AppBundle\Form;


use AppBundle\Entity\DigestWriter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class AssignDigestWriterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('writer', EntityType::class, array(
                'class' => DigestWriter::class,
                'choice_label' => 'name'
            ))
            ->add('dueDate', DateType::class, [
                // render as a single text box
                'widget' => 'single_text'
            ])
            ->add('save digest info', SubmitType::class);
    }
}

