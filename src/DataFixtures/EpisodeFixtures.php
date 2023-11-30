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

        for($categoryNumber = 1; $categoryNumber <= 6; $categoryNumber++) {
            for($programNumber = 1; $programNumber <= 5; $programNumber++) {
                for($seasonNumber = 1; $seasonNumber <= 5; $seasonNumber++) {
                    for($episodeNumber = 1; $episodeNumber <= 10; $episodeNumber++) {

                        $episode = new Episode();
                        $episode->setSeason($this->getReference('category_' . $categoryNumber . 'program_' . $programNumber . 'season_' . $seasonNumber));        
                        $episode->setTitle($faker->title());        
                        $episode->setNumber($episodeNumber);
                        $episode->setSynopsis($faker->paragraphs(3, true));
                        $manager->persist($episode);
                    }
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
