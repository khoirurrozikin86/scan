<?php

namespace App\Domain\UserOutlets\Services;

use App\Domain\UserOutlets\Actions\SyncUserOutletAction;
use App\Domain\UserOutlets\DTOs\UserOutletData;
use App\Models\User;

class UserOutletService
{
    public function __construct(
        protected SyncUserOutletAction $sync
    ) {}

    public function sync(
        User $user,
        array $payload
    ): User {
        return ($this->sync)(
            $user,
            UserOutletData::fromArray($payload)
        );
    }
}
