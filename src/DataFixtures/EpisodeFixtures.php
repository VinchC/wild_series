<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $episode = new Episode();
        $episode->setSeason($this->getReference('season1_Wednesday'));        
        $episode->setTitle("Le premier de la saison 1");        
        $episode->setNumber(1);
        $episode->setSynopsis("Synopsis de l'épisode 1 de la saison 1 de Wednesday");
        $manager->persist($episode);

        $episode2 = new Episode();
        $episode2->setSeason($this->getReference('season1_Wednesday'));        
        $episode2->setTitle("Le second de la saison 1");        
        $episode2->setNumber(2);
        $episode2->setSynopsis("Synopsis de l'épisode 2 de la saison 1 de Wednesday");
        $manager->persist($episode2);

        $episode3 = new Episode();
        $episode3->setSeason($this->getReference('season2_Wednesday'));        
        $episode3->setTitle("Le premier de la saison 2");        
        $episode3->setNumber(1);
        $episode3->setSynopsis("Synopsis de l'épisode 1 de la saison 2 de Wednesday");
        $manager->persist($episode3);

        $episode4 = new Episode();
        $episode4->setSeason($this->getReference('season2_Wednesday'));        
        $episode4->setTitle("Le second de la saison 2");        
        $episode4->setNumber(2);
        $episode4->setSynopsis("Synopsis de l'épisode 2 de la saison 2 de Wednesday");
        $manager->persist($episode4);

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
