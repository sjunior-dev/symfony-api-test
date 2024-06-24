<?php

namespace App\CoreAPI\DTO\Interface;

use App\CoreAPI\Entity\Interface\EntityInterface;

interface ResourceDTOInterface
{
    public static function fromProperties(EntityInterface $entity): self;
}
