<?php

declare(strict_types=1);

namespace App\Tests;

use App\Factory\UserFactory;
use App\Tests\Utils\DatabaseAndAPITestCase;

class UserAdminTest extends DatabaseAndAPITestCase
{
    public function testGetUser(): void
    {
        $entity = UserFactory::createOne(['email' => 'sjunior.dev@gmail.com', 'roles' => ['ROLE_ADMIN']]);

        $client = static::createClient([], ['CONTENT_TYPE' => 'application/json']);
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            [],
            json_encode([
                'email' => $entity->getEmail(),
                'password' => 'test123',
            ])
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('token', $data);

        $client->request(
            'GET',
            '/api/v1/user',
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . $data['token']],
        );
        $user = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($user['data']['email'] === 'sjunior.dev@gmail.com');

        $client->request(
            'GET',
            '/api/admin/v1/' . $user['data']['reference'] . '/user',
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . $data['token']],
        );
        $user = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($user['data']['email'] === 'sjunior.dev@gmail.com');
    }
}
