<?php

namespace App\Form;

use App\Entity\Program;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Category;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Indiquez un titre',
                ],
                'label' => 'Titre'
                ])
            ->add('synopsis', TextType::class, [
                'attr' => [
                    'placeholder' => 'Indiquez le synopsis',
                ],
                'label' => 'Synopsis'
                ])
            ->add('poster', TextType::class, [
                    'label' => 'Image'
                ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class, 
                'choice_label' => 'name'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
