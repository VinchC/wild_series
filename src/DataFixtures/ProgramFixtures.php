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
        ['title' => 'Oz', 'synopsis' => 'Dans la prison d\'Oswald, surnommée Oz, Tim McManus a créé un quartier expérimental appelé Emerald City, censé améliorer les conditions de vie des détenus pour les aider à changer. Mais..', 'familyShow' => false, 'poster' => 'oz-657b070ab4706694653687.jpg', 'officialWebsite' => 'http://www.oz.com', 'category' => 'Action'],
        ['title' => 'Sopranos', 'synopsis' => 'Tony Soprano, gangster habitant dans le New Jersey, souffre de crises de panique et doit voir en secret une psychologue, le docteur Jennifer Melfi. ', 'familyShow' => false, 'poster' => 'sopranos-657dd6bf73e65675112883.jpg', 'officialWebsite' => 'http://www.sopranos.com','category' => 'Policier'],
        ['title' => 'The Get Down', 'synopsis' => 'En 1977, le monde entend déferler les beats électriques de six adolescents passionnés qui créent, du fond du Bronx, un son déchaîné et une nouvelle forme d\'expression.', 'poster' => 'the-get-down-6581786316758415556932.jpg',  'familyShow' => true, 'officialWebsite' => 'http://www.thegetdown.com', 'category' => 'Musique'],
        ['title' => 'Lost', 'synopsis' => 'Après le crash de leur avion sur une île perdue, les survivants doivent apprendre à cohabiter et survivre dans cet environnement hostile. Bien vite, ils se rendent compte qu\'une menace semble planer sur l\'île...', 'poster' => 'lost-65817908609a8028848361.jpg',  'familyShow' => false, 'officialWebsite' => 'http://www.lost.com', 'category' => 'Fantastique'],
        ['title' => 'Walking Dead', 'synopsis' => 'Rick Grimes, adjoint du shérif du comté de Kings (en Géorgie). Il se réveille d\'un coma de plusieurs semaines pour découvrir que la population a été ravagée par une épidémie inconnue qui transforme les êtres humains en morts-vivants, appelés « rôdeurs »', 'poster' => 'walking-dead-6581792ca42c9428045087.jpg',  'familyShow' => false, 'officialWebsite' => 'http://www.walkingdead.com', 'category' => 'Horreur'],
        ['title' => 'Friends', 'synopsis' => 'Ils sont six amis : Chandler et Joey, colocataires d\'un appartement dans Manhattan, Monica et Phoebe, qui partagent l\'appartement d\'en face. Et puis il y a Ross, le frère de Monica.', 'poster' => 'friends-6581793ac059c856059797.jpg',  'familyShow' => true, 'officialWebsite' => 'http://www.friends.com', 'category' => 'Comedie']         
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
                $program->setSynopsis($programData['synopsis']);
                $program->setFamilyShow($programData['familyShow']);
                $program->setOfficialWebsite($programData['officialWebsite']);
                $program->setPoster($programData['poster']);
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
