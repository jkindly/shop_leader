<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var Generator
     */
    protected $faker;

    abstract protected function loadData(ObjectManager $em);

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create();

        $this->loadData($manager);
    }

    protected function createMany(int $count, string $groupName, callable $factory)
    {
        for ($i = 0; $i < $count; $i++)
        {
            $entity = $factory($i);

            if (null === $entity)
            {
                throw new \LogicException('Did you forget to return the entity object from BaseFicture::createMany()?');
            }

            $this->manager->persist($entity);
            $this->addReference(sprintf('%s_%d', $groupName, $i), $entity);
        }
    }
}