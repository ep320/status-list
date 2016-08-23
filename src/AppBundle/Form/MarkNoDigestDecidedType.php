<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class MarkNoDigestDecidedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('noDigestReason', ChoiceType::class, array(
                    'choices' => ['Author refused' => 'Author refused',
                        'Questions not asked' => 'Questions not asked',
                        'No response from author' => 'No response from author',
                    'Features team decision' => 'Features team decision']
                )
            )
            ->add('save no digest data', SubmitType::class);
    }
}

