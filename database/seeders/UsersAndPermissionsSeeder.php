<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Permission\Models\{Role, Permission};
use Spatie\Permission\PermissionRegistrar;

class UsersAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'web';
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // === 1️⃣ ROLES ===
        $roles = collect(['user', 'admin', 'super_admin'])
            ->mapWithKeys(fn($r) => [$r => Role::firstOrCreate(['name' => $r, 'guard_name' => $guard])]);

        // === 2️⃣ PERMISSIONS ===
        $modules = [
            'dashboard'  => ['view'],
            'user'       => ['menu', 'create', 'read', 'update', 'delete'],
            'role'       => ['menu', 'create', 'read', 'update', 'delete'],
            'permission' => ['menu', 'create', 'read', 'update', 'delete'],
            'outlets'    => ['view', 'create', 'update', 'delete'],
            'ticket-qrcode' => ['view', 'create', 'update', 'delete'],
            'user-outlets' => ['view', 'create', 'update', 'delete'],
            'scan-records' => ['view', 'create', 'update', 'delete'],





        ];

        foreach ($modules as $group => $actions) {
            foreach ($actions as $action) {
                $name = "{$group}.{$action}";
                $perm = Permission::firstOrCreate(['name' => $name, 'guard_name' => $guard]);
                if (Schema::hasColumn($perm->getTable(), 'group_name') && $perm->group_name !== $group) {
                    $perm->group_name = $group;
                    $perm->save();
                }
            }
        }

        // === 3️⃣ ROLE → PERMISSION MAPPING ===

        // 👤 USER: hanya bisa lihat (read/view)
        $roles['user']->syncPermissions([
            'dashboard.view',
            'outlets.view',
            'ticket-qrcode.view',
            'user-outlets.view',
            'scan-records.view',
            'scan-records.create',
            'scan-records.update',
            'scan-records.delete'
        ]);

        // 👨‍💼 ADMIN: CRUD penuh semua modul utama
        $roles['admin']->syncPermissions([
            'dashboard.view',

            // 'user.menu',
            // 'user.create',
            // 'user.read',
            // 'user.update',
            // 'user.delete',
            // 'role.menu',
            // 'role.create',
            // 'role.read',
            // 'role.update',
            // 'role.delete',




            'outlets.view',
            'outlets.create',
            'outlets.update',
            'outlets.delete',

            'ticket-qrcode.view',
            'ticket-qrcode.create',
            'ticket-qrcode.update',
            'ticket-qrcode.delete',

            'user-outlets.view',
            'user-outlets.create',
            'user-outlets.update',
            'user-outlets.delete',

            'scan-records.view',
            'scan-records.create',
            'scan-records.update',
            'scan-records.delete'


        ]);

        // 👑 SUPER ADMIN: semua permission
        $roles['super_admin']->syncPermissions(Permission::pluck('name')->all());

        // === 4️⃣ USER DEFAULT & ADMIN & SUPER ===
        $super = User::updateOrCreate(
            ['email' => 'super@example.com'],
            ['name' => 'Super Admin Stylus', 'password' => Hash::make('password')]
        );
        $super->syncRoles(['super_admin']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin Stylus', 'password' => Hash::make('password')]
        );
        $admin->syncRoles(['admin']);

        // === 5️⃣ USER PER LOKASI ===
        // $lokasiUsers = [
        //     ['name' => 'admin',  'email' => 'admin@gmail.com',  'server_id' => 2],  // NAMAR
        //     ['name' => 'Heru',  'email' => 'heru@gmail.com',  'server_id' => 1],  // ASRI
        //     ['name' => 'Joyo',  'email' => 'joyo@gmail.com',  'server_id' => 4],  // BREYON
        //     ['name' => 'Risfa', 'email' => 'risfa@gmail.com', 'server_id' => 5],  // TLOGO
        //     ['name' => 'Heri',  'email' => 'heri@gmail.com',  'server_id' => 6],  // HERI
        //     ['name' => 'Dika',  'email' => 'dika@gmail.com',  'server_id' => 12], // PABELAN
        //     ['name' => 'Faris', 'email' => 'faris@gmail.com', 'server_id' => 13], // OZ
        // ];

        // foreach ($lokasiUsers as $u) {
        //     $user = User::updateOrCreate(
        //         ['email' => $u['email']],
        //         [
        //             'name' => $u['name'],
        //             'password' => Hash::make('password'),
        //             'server_id' => $u['server_id'],
        //         ]
        //     );
        //     $user->syncRoles(['user']);
        //     $this->command->info("✅ User {$u['name']} ({$u['email']}) dibuat untuk server_id {$u['server_id']}");
        // }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->command->info('🎉 Semua roles, permissions, dan users berhasil disetup!');
    }
}
