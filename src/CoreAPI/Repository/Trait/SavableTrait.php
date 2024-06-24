<?php

namespace App\CoreAPI\Repository\Trait;

use App\CoreAPI\Entity\Interface\EntityInterface;

trait SavableTrait
{
    public function save(EntityInterface $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
