<?php

namespace App\CoreAPI\DTO;

use App\CoreAPI\DTO\Interface\ResourceDTOInterface;
use App\CoreAPI\Entity\Interface\EntityInterface;
use App\CoreAPI\Helper\EntityBuilderHelper;

abstract class BaseDTO implements ResourceDTOInterface
{
    // private array $filters = [
    //     'name' => [
    //         'field' => 'name',
    //         'options' => [],
    //     ],
    //     'status' => [
    //         'field' => 'plannedEndDate',
    //         'options' => [
    //             'ongoing',
    //             'completed',
    //         ],
    //         'transformers' => [
    //             'ongoing' => [
    //                 'value' => (new \DateTime())->format('Y-m-d'),
    //                 'operator' => '>',
    //             ],
    //             'completed' => [
    //                 'value' => (new \DateTime())->format('Y-m-d'),
    //                 'operator' => '<=',
    //             ],
    //         ],
    //     ],
    // ];

    public function __construct(
        private EntityBuilderHelper $entityBuilderHelper,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /** @param array<string, mixed> $data */
    public function toEntity(EntityInterface $entity, array $data): EntityInterface
    {
        return $this->entityBuilderHelper->build($entity::class, $data);
    }
}
