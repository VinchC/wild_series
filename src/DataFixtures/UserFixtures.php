<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public const ROLES = [
        ['role' => 'ROLE_ADMIN', 'email' => 'admin@monsite.com', 'password' => 'adminpassword'],
        ['role' => 'ROLE_USER', 'email' => 'user@monsite.com', 'password' => 'userpassword'],
        ['role' => 'ROLE_CONTRIBUTOR', 'email' => 'contributor@monsite.com', 'password' => 'contributorpassword']
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();        
        
        foreach(self::ROLES as $key => $rolesData) { 
            $user = new User();
            $user->setEmail($rolesData['email']);
            $user->setRoles([$rolesData['role']]);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user, 
                $rolesData['password']
            );

            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
