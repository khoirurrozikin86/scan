<?php

namespace App\Domain\UserOutlets\DTOs;

class UserOutletData
{
    public function __construct(
        public int $user_id,
        public array $outlet_ids = [],
    ) {}

    public static function fromArray(array $a): self
    {
        return new self(
            user_id: (int) ($a['user_id'] ?? 0),

            outlet_ids: array_map(
                'intval',
                $a['outlet_ids'] ?? []
            ),
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'outlet_ids' => $this->outlet_ids,
        ];
    }
}
