<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\DTOs\UserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function __invoke(UserData $data): User
    {
        $user = new User();
        $user->name  = $data->name;
        $user->email = $data->email;
        $user->password = Hash::make($data->password && trim($data->password) !== ''
            ? $data->password
            : str()->random(12)
        );
        // Jangan set $user->active jika kolomnya tidak ada
        $user->save();

        // optional: sync roles (Spatie)
        if (!empty($data->roles) && method_exists($user, 'syncRoles')) {
            $user->syncRoles($data->roles);
        }

        return $user;
    }
}
