<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Season;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($programNumber = 0; $programNumber < count(ProgramFixtures::PROGRAMS); $programNumber++) {
            $programName = $this->slugger->slug(ProgramFixtures::PROGRAMS[$programNumber]['title']);
            for($seasonNumber = 1; $seasonNumber <= 5; $seasonNumber++) {
                $season = new Season();
                $season->setNumber($seasonNumber);
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraphs(1, true));
                $season->setProgram($this->getReference('program_' . $programName));
                $manager->persist($season);
                $this->addReference('program_' . $programName . 'season_' . $seasonNumber, $season);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
           ProgramFixtures::class,
        ];
    }
}