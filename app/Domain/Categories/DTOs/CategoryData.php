<?php

namespace App\Domain\Categories\DTOs;

class CategoryData
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public bool $is_active = true,
    ) {}

    public static function fromArray(array $a): self
    {
        return new self(
            name: trim((string) ($a['name'] ?? '')),
            description: !empty($a['description'])
                ? trim((string) $a['description'])
                : null,
            is_active: filter_var(
                $a['is_active'] ?? true,
                FILTER_VALIDATE_BOOLEAN
            ),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];
    }
}