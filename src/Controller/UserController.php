<?php

namespace App\Controller;

use App\Entity\User;
use App\Response\UserResponse;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1', name: 'api_v1_', format: 'json')]
class UserController extends AbstractController
{
    public function __construct(
        public readonly UserService $userService
    ) {
    }

    #[Route('/user', name: 'user_view', methods: ['GET'])]
    public function view(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json([
            'data' => UserResponse::fromUserEntity($user),
        ]);
    }

    #[Route('/user', name: 'user_update', methods: ['PUT'])]
    public function update(Request $request): Response
    {
        $user = $this->getUser();
        $user = $this->userService->store($request, $user);

        return $this->json([
            'data' => UserResponse::fromUserEntity($user),
        ]);
    }
}
