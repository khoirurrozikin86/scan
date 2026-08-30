<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\{Role, Permission};

class SyncPermissions extends Command
{

    protected $signature = 'app:permissions-sync {--purge : Hapus permission yang tidak ada di config}';
    protected $description = 'Sync roles & permissions from config(acl.php) to database';

    public function handle(): int
    {
        $map = config('acl.map', []);
        $all = collect(config('acl.permissions', []))->flatten()->unique()->values();

        if ($all->isEmpty()) {
            $this->warn('No permissions defined in config/acl.php');
            return self::SUCCESS;
        }

        // ensure permissions exist
        foreach ($all as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // optional: purge
        if ($this->option('purge')) {
            $deleted = Permission::whereNotIn('name', $all)->delete();
            $this->info("Purged $deleted permissions not in config.");
        }

        // sync roles
        foreach ($map as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
            $this->info("Synced role [$roleName] with " . count($perms) . " perms.");
        }

        // super_admin gets all
        if (isset($map['super_admin'])) {
            if ($super = Role::where('name', 'super_admin')->first()) {
                $super->syncPermissions(Permission::all());
            }
        }

        // reset cache
        $store = config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null;
        app('cache')->store($store)->forget(config('permission.cache.key'));

        $this->info('Permissions synced.');
        return self::SUCCESS;
    }
}
