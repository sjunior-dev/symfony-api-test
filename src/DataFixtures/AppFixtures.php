<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['email' => 'sjunior.admin@gmail.com', 'roles' => ['ROLE_ADMIN']]);
        UserFactory::createOne(['email' => 'sjunior.user@gmail.com', 'roles' => ['ROLE_USER']]);
        UserFactory::createMany(10);
    }
}
