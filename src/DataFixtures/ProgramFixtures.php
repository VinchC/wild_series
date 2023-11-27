<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $program = new Program();
        $program->setTitle('Oz');
        $program->setSynopsis('A jamais la première');
        $program->setCategory($this->getReference('category_Action'));
        $manager->persist($program);

        $program2 = new Program();
        $program2->setTitle('Sopranos');
        $program2->setSynopsis('Toujours aussi bien');
        $program2->setCategory($this->getReference('category_Policier'));
        $manager->persist($program2);

        $program3 = new Program();
        $program3->setTitle('The Get Down');
        $program3->setSynopsis('Dommage qu\'elle ait été interrompue');
        $program3->setCategory($this->getReference('category_Musique'));
        $manager->persist($program3);

        $program4 = new Program();
        $program4->setTitle('Walking Dead');
        $program4->setSynopsis('Ca partait pas mal');
        $program4->setCategory($this->getReference('category_Horreur'));
        $manager->persist($program4);

        $program5 = new Program();
        $program5->setTitle('Wednesday');
        $program5->setSynopsis('Connais pas c\'est pour varier les catégories');
        $program5->setCategory($this->getReference('category_Fantastique'));
        $manager->persist($program5);

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
        ];
    }
}
