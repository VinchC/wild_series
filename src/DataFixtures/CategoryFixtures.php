<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        '1' => 'Action',
        '2' => 'Policier',
        '3' => 'Musique',
        '4' => 'Fantastique',
        '5' => 'Horreur',
        '6' => 'Romance'
    ];

    public function load(ObjectManager $manager)
    {
        foreach(self::CATEGORIES as $categoryNumber => $categoryName) { 
            $category = new Category();
            $category->setName($categoryName); 
            $manager->persist($category);
            $this->addReference('category_' . $categoryNumber, $category);
        }
        $manager->flush();
    }
}