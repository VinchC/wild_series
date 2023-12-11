<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

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

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        {
            $faker = Factory::create();

            foreach(self::PROGRAMS as $key => $programData) { 
                $programSlug = $this->slugger->slug($programData['title']);
                $program = new Program();
                $program->setTitle($programData['title']);
                $program->setSynopsis($faker->paragraphs(1, true));
                $program->setCategory($this->getReference('category_' . $programData['category']));
                if ($program->getOwner()) {
                    $program->setOwner($this->getReference('owner_' . $program->getOwner()));
                };
                $program->setSlug($programSlug);
                $manager->persist($program);
                $this->addReference('program_' . $programSlug, $program);
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
