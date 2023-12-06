<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($programNumber = 1; $programNumber <= count(ProgramFixtures::PROGRAMS); $programNumber++) {
            for($seasonNumber = 1; $seasonNumber <= 5; $seasonNumber++) {
                $season = new Season();
                $season->setNumber($seasonNumber);
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraphs(1, true));
                $season->setProgram($this->getReference('program_' . $programNumber));
                $manager->persist($season);
                $this->addReference('program_' . $programNumber . 'season_' . $seasonNumber, $season);
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