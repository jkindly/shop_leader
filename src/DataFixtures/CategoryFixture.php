<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public const CATEGORIES = ['CAT1', 'CAT2', 'CAT3'];

    public function load(ObjectManager $manager)
    {
        $cat1 = new Category();
        $cat1->setTitle("Odzież damska");
        $manager->persist($cat1);

        $cat2 = new Category();
        $cat2->setTitle("Odzież damska");
        $manager->persist($cat2);

        $cat3 = new Category();
        $cat3->setTitle("Odzież damska");
        $manager->persist($cat3);

        $manager->flush();

        $this->addReference(self::CATEGORIES[0], $cat1);
        $this->addReference(self::CATEGORIES[1], $cat2);
        $this->addReference(self::CATEGORIES[2], $cat3);
    }
}
