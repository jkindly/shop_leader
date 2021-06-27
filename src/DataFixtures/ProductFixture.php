<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Service\UploaderHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class ProductFixture extends BaseFixture
{
    private $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    public function loadData(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++)
        {
            $product = new Product();
            $product->setTitle('Bluza damska');
            $product->setSlug('thats-the-slug');
            $product->setPrice(rand(100, 350));
            $product->setImageFilename($this->fakeUploadImage());
            $product->setCount(rand(5, 100));
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

    private function fakeUploadImage(): string
    {
        $randomImage = rand(1, 5).'.jpg';

        $fs = new Filesystem();
        $targetPath = sys_get_temp_dir().'/'.$randomImage;
        $fs->copy(__DIR__.'/images/'.$randomImage, $targetPath, true);

        return $this->uploaderHelper
            ->uploadProductImage(new File($targetPath), null);
    }
}
