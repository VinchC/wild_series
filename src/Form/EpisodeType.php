<?php

namespace App\Form;

use App\Entity\Episode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Merci de renseigner le titre',
                ],
                'label' => 'Titre'
                ])
            ->add('number', null, [
                'attr' => [
                    'placeholder' => 'Indiquez un nombre',
                    'min' => 1,
                ],
                'label' => 'Numéro'
                ])
            ->add('synopsis', null, [
                'attr' => [
                    'placeholder' => 'Indiquez le synopsis',
                ],
                'label' => 'Synopsis'
                ])
            ->add('duration', null, [
                'attr' => [
                    'placeholder' => 'Indiquez la durée',
                ],
                'label' => 'Durée'
                ])
            ->add('season', null, [
                'label' => 'Numéro de la saison',
                'choice_label' => 'number'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
