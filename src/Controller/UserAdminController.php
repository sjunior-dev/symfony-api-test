<?php

namespace App\Controller;

use App\CoreAPI\Service\RequestService;
use App\DTO\User\UserResponseDTO;
use App\Entity\User;
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
        public readonly RequestService $requestService
    ) {
    }

    #[Route('/users', name: 'user_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json([
            'data' => $this->requestService->all(User::class),
        ]);
    }

    #[Route('/user', name: 'user_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->requestService->store($request);

        return $this->json([
            'data' => UserResponseDTO::fromUserEntity($user),
        ], Response::HTTP_CREATED);
    }

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: UserResponseDTO::class)
    )]
    #[Route('/{reference}/user', name: 'user_view', methods: ['GET'])]
    public function view(User $user): JsonResponse
    {
        /** @var User $user */
        return $this->json([
            'data' => UserResponseDTO::fromUserEntity($user),
        ]);
    }

    #[Route('/{reference}/user', name: 'user_update', methods: ['PUT'])]
    public function update(User $user, Request $request): JsonResponse
    {
        $user = $this->requestService->store($request, $user);

        return $this->json([
            'data' => UserResponseDTO::fromUserEntity($user),
        ]);
    }

    #[Route('/{reference}/user', name: 'user_delete', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $this->requestService->remove($user);

        return $this->json([
            'data' => UserResponseDTO::fromUserEntity($user),
        ]);
    }
}
