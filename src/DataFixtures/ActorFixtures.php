<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        {
            $faker = Factory::create();

              for($actorNumber = 1; $actorNumber <= 10; $actorNumber++) {
                $actor = new Actor();
                $actor->setFirstName($faker->firstName());
                $actor->setLastName($faker->lastName());
                $actor->setBirthDate($faker->dateTime());
                for ($programNumber = 1; $programNumber <= 3; $programNumber++) {
                    $actor->addProgram($this->getReference('category_' . 1 . 'program_' . $programNumber));
                }
                $manager->persist($actor);
            }
    
            $manager->flush();
        }
    }
    
    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }
}
