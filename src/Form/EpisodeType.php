<?php

namespace App\Form;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                ],
                'label' => 'Numéro'
                ])
            ->add('synopsis', null, [
                'attr' => [
                    'placeholder' => 'Indiquez le synopsis',
                ],
                'label' => 'Synopsis'
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
