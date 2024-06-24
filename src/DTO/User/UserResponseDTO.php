<?php

namespace App\DTO\User;

use App\CoreAPI\Entity\Interface\EntityInterface;
use App\Entity\User;

class UserResponseDTO
{
    /**
     *
     * @param array<int, string> $roles
     */
    public function __construct(
        public string $id,
        public string $email,
        public array $roles,
    ) {
    }

    /** @param EntityInterface $user */
    public static function fromUserEntity(EntityInterface $user): self
    {
        /** @var User $user */
        return new self(
            $user->getReference()->toRfc4122(),
            $user->getEmail(),
            $user->getRoles()
        );
    }
}
