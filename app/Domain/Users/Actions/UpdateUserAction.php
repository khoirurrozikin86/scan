<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\DTOs\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function __invoke(User $user, UserData $data): User
    {
        $user->name  = $data->name;
        $user->email = $data->email;

        if ($data->password && trim($data->password) !== '') {
            $user->password = Hash::make($data->password);
        }

        $user->save();

        if (!empty($data->roles) && method_exists($user, 'syncRoles')) {
            $user->syncRoles($data->roles);
        }

        return $user;
    }
}
