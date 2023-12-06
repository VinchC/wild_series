<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($programNumber = 1; $programNumber <= count(ProgramFixtures::PROGRAMS); $programNumber++) {
            for($seasonNumber = 1; $seasonNumber <= 5; $seasonNumber++) {
                for($episodeNumber = 1; $episodeNumber <= 10; $episodeNumber++) {

                    $episode = new Episode();
                    $episode->setSeason($this->getReference('program_' . $programNumber . 'season_' . $seasonNumber));        
                    $episode->setTitle($faker->title());        
                    $episode->setNumber($episodeNumber);
                    $episode->setSynopsis($faker->paragraphs(1, true));
                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont EpisodeFixtures dépend
        return [
          SeasonFixtures::class,
        ];
    }
}
