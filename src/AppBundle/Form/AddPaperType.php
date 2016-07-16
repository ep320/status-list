<?php
namespace AppBundle\Form;

use AppBundle\AppBundle;
use AppBundle\Entity\ArticleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->
            add('subjectArea1')
            ->add('subjectArea2')
            ->add('save', SubmitType::class);
    }
}

