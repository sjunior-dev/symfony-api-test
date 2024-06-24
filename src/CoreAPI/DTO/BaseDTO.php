<?php

namespace App\CoreAPI\DTO;

use App\CoreAPI\DTO\Interface\ResourceDTOInterface;
use App\CoreAPI\Entity\Interface\EntityInterface;

use function Symfony\Component\String\u;

abstract class BaseDTO implements ResourceDTOInterface
{
    protected array $transformers = [];
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

    // public function __construct(
    //     private EntityBuilderHelper $entityBuilderHelper,
    // ) {
    // }


    public static function fromProperties(EntityInterface $entity): self
    {
        $dto = new static();
        $reflection = new \ReflectionClass($dto);

        foreach ($reflection->getProperties() as $property) {
            if (!$property->isPublic()) continue;

            $dtoProperty = $property->getName();
            $entityProperty = $dto->transformers[$property->getName()] ?? $property->getName();

            $entityPropertyNormalized = u($entityProperty)->camel()->title();

            $getFunction = "get$entityPropertyNormalized";
            if (method_exists($entity::class, $getFunction)) {

                if (property_exists($dto::class, $dtoProperty)) {

                    $content = $entity->{$getFunction}();
                    // if (preg_match('/Entity/', $property->getType()->getName())) {
                    // if ($content instanceof EntityInterface) {
                    //     $name = u($dtoProperty)->camel()->title();
                    //     $classDTO = '\\App\DTO\\' . $name . '\\'.$name. 'ResponseDTO';
                    //     $dto->{$dtoProperty} = $classDTO::fromProperties($entity->{$getFunction}());
                    //     dd($dto);
                    //     continue;
                    // }
                    $dto->{$dtoProperty} = $content;
                }

                continue;
            }
        }

        return $dto;
    }
}
