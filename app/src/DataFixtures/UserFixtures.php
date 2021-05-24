<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin')
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$0gWs4U8Y/hPPlCZLyYDPOw$VQBafo42oJqHnWu6rXvUarPYP9/wX5la7LsYFHBAKXA'); // secret

        $manager->persist($user);

        $manager->flush();
    }
}
