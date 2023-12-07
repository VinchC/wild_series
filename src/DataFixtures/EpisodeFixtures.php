<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
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
                for($episodeNumber = 1; $episodeNumber <= 10; $episodeNumber++) {
                    $episode = new Episode();
                    $episode->setSeason($this->getReference('program_' . $programName . 'season_' . $seasonNumber));        
                    $episode->setTitle($faker->title());
                    $episodeSlug = $this->slugger->slug($episode->getTitle());        
                    $episode->setNumber($episodeNumber);
                    $episode->setDuration(52);
                    $episode->setSynopsis($faker->paragraphs(1, true));
                    $episode->setSlug($episodeSlug);
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
