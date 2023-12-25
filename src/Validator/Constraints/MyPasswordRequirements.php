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
                'pattern' => '/[#?!@$%^&*-]+/i', 
                'message' => 'Votre mot de passe doit contenir au moins un caractère spécial.'
            ]),
        ];
    }
}