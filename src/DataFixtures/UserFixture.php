<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(3, 'main_users', function($i) {
            $user = new User();
            $user->setEmail(sprintf('sanctum%d@example.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setLogin($this->faker->userName);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'engage'));
            $user->setRoles(['ROLE_ADMIN']);
            return $user;
        });

        $manager->flush();
    }
}
