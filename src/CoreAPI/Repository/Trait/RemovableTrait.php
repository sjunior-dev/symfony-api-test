<?php

namespace App\CoreAPI\Repository\Trait;

use App\CoreAPI\Entity\Interface\EntityInterface;

trait RemovableTrait
{
    public function remove(EntityInterface $entity, bool $flush = true): void
    {
        $entity->setRemovedAt(new \DateTimeImmutable());

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
