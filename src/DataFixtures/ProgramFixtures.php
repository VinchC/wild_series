<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS = [
        ['title' => 'Oz', 'category' => 'Action'],
        ['title' => 'Sopranos', 'category' => 'Policier'],
        ['title' => 'The Get Down', 'category' => 'Musique'],
        ['title' => 'Lost', 'category' => 'Fantastique'],
        ['title' => 'Walking Dead', 'category' => 'Horreur'],
        ['title' => 'Friends', 'category' => 'Comedie']         
    ];

    public function load(ObjectManager $manager)
    {
        {
            $faker = Factory::create();

            foreach(self::PROGRAMS as $key => $programData) { 
                $program = new Program();
                $program->setTitle($programData['title']);
                $program->setSynopsis($faker->paragraphs(1, true));
                $program->setCategory($this->getReference('category_' . $programData['category']));
                $manager->persist($program);
                $this->addReference('program_' . $key+=1, $program);
            }
        }
    
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [
          CategoryFixtures::class,
        ];
    }
}
