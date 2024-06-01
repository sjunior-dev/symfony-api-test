<?php

namespace App\Controller;

use App\Entity\User;
use App\Response\UserResponse;
use App\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin/v1', name: 'api_admin_v1_', format: 'json')]
class UserAdminController extends AbstractController
{
    public function __construct(
        public readonly UserService $userService
    ) {
    }

    #[Route('/users', name: 'user_list', methods: ['GET'])]
    public function list(): Response
    {

        return $this->json([
            'data' => $this->userService->all(),
        ]);
    }

    #[Route('/user', name: 'user_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->userService->store($request);

        return $this->json([
            'data' => UserResponse::fromUserEntity($user),
        ], Response::HTTP_CREATED);
    }

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: UserResponse::class)
    )]
    #[Route('/{reference}/user', name: 'user_view', methods: ['GET'])]
    public function view(User $user): Response
    {
        return $this->json([
            'data' => UserResponse::fromUserEntity($user),
        ]);
    }

    #[Route('/{reference}/user', name: 'user_update', methods: ['PUT'])]
    public function update(User $user, Request $request): Response
    {
        $user = $this->userService->store($request, $user);

        return $this->json([
            'data' => UserResponse::fromUserEntity($user),
        ]);
    }

    #[Route('/{reference}/user', name: 'user_delete', methods: ['DELETE'])]
    public function delete(User $user): Response
    {
        $this->userService->remove($user);

        return $this->json([
            'data' => UserResponse::fromUserEntity($user),
        ]);
    }
}
