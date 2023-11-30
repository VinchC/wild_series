<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        {
            $faker = Factory::create();

            for($categoryNumber = 1; $categoryNumber <= 6; $categoryNumber++) {
                for($programNumber = 1; $programNumber <= 5; $programNumber++) {
                    $program = new Program();
                    $program->setTitle($faker->title());
                    $program->setSynopsis($faker->paragraphs(3, true));
                    $program->setCategory($this->getReference('category_' . $categoryNumber));
                    $manager->persist($program);
                    $this->addReference('category_' . $categoryNumber . 'program_' . $programNumber, $program);
                }
            }
    
            $manager->flush();
        }
    }
    
    public function getDependencies()
    {
        return [
          CategoryFixtures::class,
        ];
    }
}
