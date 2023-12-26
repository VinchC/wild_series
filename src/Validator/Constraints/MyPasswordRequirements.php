<?php

use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints as Assert;

class MyPasswordRequirements extends Compound {

    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length([
                'min' => 9,
                'minMessage' => 'Votre mot de passe doit contenir au moins 9 caractères.'
            ]),
            new Assert\Regex([
                'pattern' => '/\d+/i', 
                'message' => 'Votre mot de passe doit contenir au moins un chiffre.'
            ]),
            new Assert\Regex([
                'pattern' => '/[a-z]+/', 
                'message' => 'Votre mot de passe doit contenir au moins une lettre minuscule.'
            ]),
            new Assert\Regex([
                'pattern' => '/[A-Z]+/', 
                'message' => 'Votre mot de passe doit contenir au moins une lettre majuscule.'
            ]),
            new Assert\Regex([
                'pattern' => '/[#?!@$%^&*-]+/i', 
                'message' => 'Votre mot de passe doit contenir au moins un caractère spécial.'
            ]),
            new Assert\AtLeastOneOf([
                'constraints' => [
                    new Assert\Regex(
                        pattern: '/#/', 
                        message: 'Votre mot de passe doit contenir au moins un caractère # ou un caractère $.'
                    ),
                    new Assert\Regex(
                        pattern: '/!/', 
                        message: 'Votre mot de passe doit contenir au moins un caractère # ou un caractère $.'
                    ),
                ],
                'message' => "Votre mot de passe doit respecter au moins l'une des deux contraintes suivantes :"
            ])
        ];
    }
}