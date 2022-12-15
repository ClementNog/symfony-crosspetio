<?php

namespace App\Form;

use App\Entity\Grade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RankingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('gender', ChoiceType::class, [
                'choices'  => [
                    ' ' => null,
                    'Girl' => 'F',
                    'Boy' => 'G',
                ],
            ]);
        $builder->add('grade', EntityType::class, [
            // looks for choices from this entity
            'class' => Grade::class,
        
            // uses the User.username property as the visible option string
            'choice_label' => 'Shortname',])
            ;
        $builder->add('level', ChoiceType::class, [
                'choices'  => [
                    ' ' => null,
                    '3eme' => '3',
                    '4eme' => '4',
                    '5eme' => '5',
                    '6eme' => '6',
                ],
            ]);
        $builder->add('save', SubmitType::class, [
            'attr' => ['class' => 'save'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
