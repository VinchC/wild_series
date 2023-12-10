<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'attr' => [
                    'placeholder' => 'Qu\'avez-vous pensé de cet épisode ?',
                ],
                'label' => 'Commentaire'
                ])
            ->add('rate', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Note',
                    'min' => 1,
                    'max' => 5
                ],
                'label' => 'Note (sur 5)'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
