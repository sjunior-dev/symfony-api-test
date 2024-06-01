<?php

namespace App\Response;

use App\Entity\User;

class UserResponse
{
    public function __construct(
        public string $id,
        public string $email,
        public array $roles,
    ) {
    }

    public static function fromUserEntity(User $user): self
    {
        return new self(
            $user->getReference()->toRfc4122(),
            $user->getEmail(),
            $user->getRoles()
        );
    }
}
