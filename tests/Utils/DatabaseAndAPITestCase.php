<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestAssertionsTrait;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class DatabaseAndAPITestCase extends KernelTestCase
{
    use WebTestAssertionsTrait;
    private EntityManagerInterface $entityManager;

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        //(1) boot the Symfony kernel
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        //(2) use static::getContainer() to access the service container
        $container = static::getContainer();

        //(3) get UserRepository from container.
        $this->userRepository = $container->get(UserRepository::class);
        $this->truncateEntities([
            User::class,
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        $kernel = static::bootKernel($options);

        try {
            $client = $kernel->getContainer()->get('test.client');
        } catch (ServiceNotFoundException) {
            if (class_exists(KernelBrowser::class)) {
                throw new \LogicException('You cannot create the client used in functional tests if the "framework.test" config is not set to true.');
            }

            throw new \LogicException('You cannot create the client used in functional tests if the BrowserKit component is not available. Try running "composer require symfony/browser-kit".');
        }

        $client->setServerParameters($server);

        return self::getClient($client);
    }

    private function truncateEntities(array $entities)
    {
        $connection = $this->entityManager->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');

        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $this->entityManager->getClassMetadata($entity)->getTableName()
            );
            $connection->executeQuery($query);
        }

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
    }
}
