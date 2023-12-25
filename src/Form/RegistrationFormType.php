<?php

namespace App\Form;

use App\Entity\User;
use MyPasswordRequirements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'attr' => [
                    'placeholder' => 'Renseignez votre adresse mail',
                ],
                'label' => 'Mail'
                ])
            ->add('firstName', TextType::class,  [
                'attr' => [
                    'placeholder' => 'Renseignez votre prénom',
                ],
                'label' => 'Prénom'
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes du site.',
                    ]),
                ],
                'label' => 'Accepter les termes du site'
            ])
            ->add('plainPassword', PasswordType::class, [
                'toggle' => true,
                'hidden_label' => 'Masquer',
                'visible_label' => 'Afficher',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Renseignez votre mot de passe',
                ],
                'label' => 'Mot de passe',
                'constraints' => [
                    new MyPasswordRequirements()
                ],
                'invalid_message' => 'Votre mot de passe doit contenir au moins un chiffre et un caractère spécial.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
