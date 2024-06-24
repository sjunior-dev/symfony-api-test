<?php

namespace App\DTO\User;

use App\CoreAPI\DTO\BaseDTO;

class UserResponseDTO extends BaseDTO
{
    protected array $transformers = [
        'id' => 'reference',
    ];

    public string $id;
    public string $email;
    public array $roles;
}
