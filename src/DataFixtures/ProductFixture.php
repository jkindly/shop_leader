<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++)
        {
            $product = new Product();
            $product->setTitle('Bluza damska');
            $product->setSlug('thats-the-slug');
            $product->setPrice(rand(100, 350));
            $product->setCategory($this->getReference(CategoryFixture::CATEGORIES[rand(0, 2)]));

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixture::class,
        ];
    }
}
