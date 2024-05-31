<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ValidatorInterface $validator
    ) {
    }

    public function store(Request $request, ?User $user = null): User
    {
        $body = json_decode($request->getContent(), true);

        if (!$user) {
            $user = new User();
        }

        if (isset($body['email'])) {
            $user->setEmail($body['email']);
        }

        if (isset($body['roles'])) {
            $user->setRoles($body['roles']);
        }

        if (isset($body['password'])) {
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $body['password'])
            );
        }

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }

        $this->userRepository->save($user);

        return $user;
    }
}
