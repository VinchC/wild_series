<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Program;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
            ->add('posterFile', VichFileType::class, [
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_uri' => true, // not mandatory, default is true
                'label' => 'Image'
                ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class, 
                'choice_label' => 'name'])
            ->add('actors', EntityType::class, [
                'label' => 'Acteurs',
                'class' => Actor::class, 
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
