<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Outlet;
use Illuminate\Database\Seeder;

class UserOutletSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        $superAdmins = [
            'superadmin@local.com',
            'super@example.com',
        ];

        foreach ($superAdmins as $email) {

            $user = User::where('email', $email)->first();

            if ($user) {
                $user->outlets()->sync(
                    Outlet::pluck('id')->toArray()
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN WAHANA
        |--------------------------------------------------------------------------
        */

        $admin = User::where(
            'email',
            'admin_wahana@local.com'
        )->first();

        if ($admin) {

            $admin->outlets()->sync(
                Outlet::where('outlet_code', 'like', 'DSW-%')
                    ->pluck('id')
                    ->toArray()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FINANCE
        |--------------------------------------------------------------------------
        */

        $finance = User::where(
            'email',
            'user_finance@local.com'
        )->first();

        if ($finance) {

            $outlet = Outlet::where(
                'outlet_code',
                'DSW-ADM-FIN'
            )->first();

            if ($outlet) {
                $finance->outlets()->sync([
                    $outlet->id,
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | USER OUTLET
        |--------------------------------------------------------------------------
        |
        | Mapping user ke outlet ditambahkan di sini.
        |
        */

        $mapping = [

            // Contoh:
            //
            // 'ersa@local.com' => [
            //     'DSW-OMSU',
            // ],
            //
            // 'ulfana@local.com' => [
            //     'DSW-ALSANG',
            // ],

        ];


        foreach ($mapping as $email => $outletCodes) {

            $user = User::where(
                'email',
                $email
            )->first();

            if (!$user) {
                continue;
            }

            $outletIds = Outlet::whereIn(
                'outlet_code',
                $outletCodes
            )
                ->pluck('id')
                ->toArray();

            $user->outlets()->sync($outletIds);
        }


        $this->command?->info(
            'User outlet access seeded successfully.'
        );
    }
}
