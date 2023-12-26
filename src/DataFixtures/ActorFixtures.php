<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Actor;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    
    public function load(ObjectManager $manager)
    {
        {
            $faker = Factory::create();

              for($actorNumber = 1; $actorNumber <= 10; $actorNumber++) {
                $actor = new Actor();
                $actor->setFirstName($faker->firstName());
                $actor->setLastName($faker->lastName());
                $actor->setExperience(2);
                $actor->setBirthDate($faker->dateTime());
                for ($programNumber = 0; $programNumber < 3; $programNumber++) {
                    $programName = $this->slugger->slug(ProgramFixtures::PROGRAMS[$programNumber]['title']);
                    $actor->addProgram($this->getReference('program_' . $programName));
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
