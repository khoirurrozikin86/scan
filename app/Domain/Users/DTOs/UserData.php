<?php

namespace App\Domain\Users\DTOs;

class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
        public array $roles = [], // kalau pakai spatie/roles

    ) {}

    public static function fromArray(array $a): self
    {
        return new self(
            name: $a['name'],
            email: $a['email'],
            password: $a['password'] ?? null,
            roles: $a['roles'] ?? [],
        );
    }
}
