<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Program;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ActorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'placeholder' => 'Indiquez le prénom',
                ],
                'label' => 'Prénom'
                ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'placeholder' => 'Indiquez le nom de famille',
                ],
                'label' => 'Nom de famille'
                ])
            ->add('experience', ChoiceType::class, [
                'choices' => Actor::EXPERIENCE,
                'attr' => [
                    'placeholder' => "Indiquez l'expérience parmi les choix proposés.",
                ],
                'label' => 'Expérience'
                ])
            ->add('birth_date', null, [
                'attr' => [
                    'placeholder' => 'Indiquez la date de naissance',
                ],
                'label' => 'Date de naissance'
                ])
            ->add('posterFile', VichFileType::class, [
                'required'      => false,
                'allow_delete'  => false,
                'download_uri' => false,
                'label' => 'Portrait'
                ])
            ->add('programs', EntityType::class, [
                'class' => Program::class,
'choice_label' => 'id',
'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'sanitize_html' => true,
            'data_class' => Actor::class,
        ]);
    }
}
