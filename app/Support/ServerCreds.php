<?php

namespace App\Support;

use App\Models\Server;
use Illuminate\Contracts\Encryption\DecryptException;

class ServerCreds
{
    public static function from(Server $s): array
    {
        $pass = $s->password;
        try {
            $pass = decrypt($pass);
        } catch (DecryptException $e) { /* plaintext, biarin */
        }
        return [
            'host' => $s->ip,
            'user' => $s->user,
            'pass' => $pass,
            'port' => (int)($s->port ?? 8728),
            'ssl'  => (bool)($s->ssl ?? false),
        ];
    }
}
