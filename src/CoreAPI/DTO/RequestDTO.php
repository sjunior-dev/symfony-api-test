<?php

declare(strict_types=1);

namespace App\CoreAPI\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RequestDTO extends BaseDTO
{
    /** @var array<int, mixed> $filters */
    #[Assert\Type('array')]
    private array $filters;

    /** @var array<string, mixed> $sort */
    #[Assert\NotBlank]
    #[Assert\Type('array')]
    private array $sort;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(0)]
    private int $start;


    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\GreaterThan(0)]
    private int $size;

    /** @var array<int, mixed> $data */
    private array $data = [];

    /**
     * @param array<int, mixed> $filters
     * @param array<string, mixed> $sort
     */
    public function __construct(
        mixed $data,
        array $filters,
        array $sort,
        int $start,
        int $size,
    ) {
        $this->filters = $filters;
        $this->sort = $sort;
        $this->start = $start;
        $this->size = $size;
        $this->data = $data;
    }

    /** @return array<int, mixed> */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /** @return array<string, mixed> */
    public function getSort(): array
    {
        return $this->sort;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    /** @return array<int, mixed> */
    public function getData(): array
    {
        return $this->data;
    }
}
