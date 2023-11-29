<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $season = new Season();
        $season->setProgram($this->getReference('program_Wednesday'));        
        $season->setNumber(1);
        $season->setYear(2000);
        $season->setDescription("Saison 1 de Wednesday");
        $manager->persist($season);
        $this->addReference('season1_Wednesday', $season);

        $season2 = new Season();
        $season2->setProgram($this->getReference('program_Wednesday'));        
        $season2->setNumber(2);
        $season2->setYear(2001);
        $season2->setDescription("Saison 2 de Wednesday");
        $manager->persist($season2);
        $this->addReference('season2_Wednesday', $season2);

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont SeasonFixtures dépend
        return [
          ProgramFixtures::class,
        ];
    }
}
