<?php

namespace App\Domain\UserOutlets\Actions;

use App\Domain\UserOutlets\DTOs\UserOutletData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SyncUserOutletAction
{
    public function __invoke(
        User $user,
        UserOutletData $data
    ): User {
        return DB::transaction(function () use ($user, $data) {

            $user->outlets()->sync(
                $data->outlet_ids
            );

            return $user->load('outlets');
        });
    }
}
