<?php

namespace App\CoreAPI\Service;

use App\CoreAPI\Entity\Interface\EntityInterface;
use App\CoreAPI\Repository\Interface\RepositoryInterface;
use App\DTO\User\UserResponseDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $entityManagerInterface,
    ) {
    }

    public function remove(EntityInterface $entity): void
    {
        /** @var RepositoryInterface $repository */
        $repository = $this->entityManagerInterface->getRepository($entity::class);
        $repository->remove($entity);
    }

    /**
     * @param class-string<EntityInterface> $entityClass
     * @return array<int|null, EntityInterface>
     * */
    public function all(string $entityClass): array
    {
        $repository = $this->entityManagerInterface->getRepository($entityClass);

        return $repository->findAll();

        // foreach ($users as $user) {
        //     $response[] = UserResponseDTO::fromUserEntity($user);
        // }

        // return $response;
    }

    public function store(Request $request, ?User $user = null): User
    {
        $body = json_decode($request->getContent(), true);

        if (!$user) {
            $user = new User();
        }

        if (!empty($body['email'])) {
            $user->setEmail($body['email']);
        }

        if (!empty($body['roles'])) {
            $user->setRoles($body['roles']);
        }

        if (!empty($body['password'])) {
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $body['password'])
            );
        }

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }

        // $this->userRepository->save($user);

        return $user;
    }
}
