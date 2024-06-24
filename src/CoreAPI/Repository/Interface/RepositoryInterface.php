<?php

namespace App\CoreAPI\Repository\Interface;

use App\CoreAPI\Entity\Interface\EntityInterface;

interface RepositoryInterface
{
    public function save(EntityInterface $entity, bool $flush = true): void;

    public function remove(EntityInterface $entity, bool $flush = true): void;
}
