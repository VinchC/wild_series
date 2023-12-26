<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', null, [
                'attr' => [
                    'placeholder' => 'Indiquez un nombre',
                    'min' => 1,
                ],
                'label' => 'Numéro'
                ])
            ->add('year', null, [
                'attr' => [
                    'placeholder' => 'Indiquez une année',
                ],
                'label' => 'Année'
                ])
            ->add('description', null, [
                'attr' => [
                    'placeholder' => 'Indiquez une description',
                ],
                'label' => 'Description'
                ])
            ->add('program', null, [
                'choice_label' => 'title',
                'label' => 'Série'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
