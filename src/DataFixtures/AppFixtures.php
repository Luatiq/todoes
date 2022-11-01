<?php

namespace App\DataFixtures;

use App\Entity\AdminUser;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = new AdminUser();
        $pass = $this->passwordHasher->hashPassword($adminUser, 'Test123!');

        $adminUser
            ->setDisplay('Lua')
            ->setEmail('lua@luati.cc')
            ->setPassword($pass)
        ;

        $manager->persist($adminUser);
        $manager->flush();

        $this->createRandUsers($manager, 5);
    }

    private function createRandUsers(
        ObjectManager $manager,
        int $amount
    ) {
        // Todo create actual random users, Alicebundle fork or without?
        for ($i = 1; $i <= $amount; $i++) {
            $user = new User();
            $user
                ->setDisplay('User '.$i)
                ->setEmail('user'.$i.'@luati.cc')
                ->setPassword($this->passwordHasher->hashPassword($user, 'Test123!'))
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }
}
