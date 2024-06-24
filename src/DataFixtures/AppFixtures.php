<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /** @phpstan-ignore staticMethod.unresolvableReturnType */
        UserFactory::createOne(['email' => 'sjunior.admin@gmail.com', 'roles' => ['ROLE_ADMIN']]);
        /** @phpstan-ignore staticMethod.unresolvableReturnType */
        UserFactory::createOne(['email' => 'sjunior.user@gmail.com', 'roles' => ['ROLE_USER']]);
        /** @phpstan-ignore staticMethod.unresolvableReturnType */
        UserFactory::createMany(10);
    }
}
