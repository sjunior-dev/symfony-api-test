<?php

namespace App\CoreAPI\DTO\Interface;

use App\CoreAPI\Entity\Interface\EntityInterface;

interface ResourceDTOInterface
{

    /** @return array<string, mixed> */
    public function toArray(): array;

    /** @param array<string, mixed> $data */
    public function toEntity(EntityInterface $entity, array $data): EntityInterface;
}
