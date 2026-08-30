<?php

namespace App\Domain\Users\Actions;

use App\Models\User;

class DeleteUserAction
{
    public function __invoke(User $user): void
    {
        $user->delete();
    }
}
