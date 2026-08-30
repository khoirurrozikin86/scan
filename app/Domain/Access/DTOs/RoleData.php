<?php

// app/Domain/Access/DTOs/RoleData.php
namespace App\Domain\Access\DTOs;

class RoleData
{
    public function __construct(public string $name) {}
    public static function from(array $payload): self
    {
        return new self(name: $payload['name']);
    }
}
