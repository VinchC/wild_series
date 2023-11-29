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
        $this->addReference('program_Oz', $program);

        $program2 = new Program();
        $program2->setTitle('Sopranos');
        $program2->setSynopsis('Toujours aussi bien');
        $program2->setCategory($this->getReference('category_Policier'));
        $manager->persist($program2);
        $this->addReference('program_Sopranos', $program2);

        $program3 = new Program();
        $program3->setTitle('The Get Down');
        $program3->setSynopsis('Dommage qu\'elle ait été interrompue');
        $program3->setCategory($this->getReference('category_Musique'));
        $manager->persist($program3);
        $this->addReference('program_The Get Down', $program3);

        $program4 = new Program();
        $program4->setTitle('Walking Dead');
        $program4->setSynopsis('Ca partait pas mal');
        $program4->setCategory($this->getReference('category_Horreur'));
        $manager->persist($program4);
        $this->addReference('program_Walking Dead', $program4);

        $program5 = new Program();
        $program5->setTitle('Wednesday');
        $program5->setSynopsis('Connais pas c\'est pour varier les catégories');
        $program5->setCategory($this->getReference('category_Fantastique'));
        $manager->persist($program5);
        $this->addReference('program_Wednesday', $program5);

        $program6 = new Program();
        $program6->setTitle('Monday');
        $program6->setSynopsis('On s\'en fout');
        $program6->setCategory($this->getReference('category_Fantastique'));
        $manager->persist($program6);
        $this->addReference('program_Monday', $program6);

        $program7 = new Program();
        $program7->setTitle('Tuesday');
        $program7->setSynopsis('On s\'en fout aussi');
        $program7->setCategory($this->getReference('category_Fantastique'));
        $manager->persist($program7);
        $this->addReference('program_Tuesday', $program7);

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
