<?php

namespace App\CoreAPI\Helper;

use App\CoreAPI\Entity\Interface\EntityInterface;
use InvalidArgumentException;

use function Symfony\Component\String\u;

class EntityBuilderHelper
{
    /**
     * @param class-string<EntityInterface> $class
     * @param array<string, mixed> $data
     * @return EntityInterface
     */
    public function build(string $class, array $data): EntityInterface
    {
        if (!class_exists($class)) {
            throw new InvalidArgumentException("Invalid class $class");
        }

        if (property_exists($class, 'fields')) {
        }
        $entity = new $class();

        foreach ($data as $key => $value) {
            $field = u($key)->camel()->title();

            $set = "set$field";
            if (method_exists($entity, $set)) {
                $entity->{$set}($value);

                continue;
            }

            $add = "add$field";
            if (method_exists($entity, $add)) {
                $entity->{$add}($value);

                continue;
            }
        }

        return $entity;
    }
}
