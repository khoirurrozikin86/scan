<?php

namespace App\Domain\UserOutlets\Actions;

use App\Domain\Audit\Services\AuditLogService;
use App\Domain\UserOutlets\DTOs\UserOutletData;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SyncUserOutletAction
{
    public function __construct(
        protected AuditLogService $auditLog,
    ) {}

    public function __invoke(
        User $user,
        UserOutletData $data
    ): User {
        return DB::transaction(function () use ($user, $data) {

            /*
            |--------------------------------------------------------------------------
            | AMBIL OUTLET SEBELUM SYNC
            |--------------------------------------------------------------------------
            */
            $oldOutletIds = $user->outlets()
                ->pluck('outlets.id')
                ->map(fn($id) => (int) $id)
                ->sort()
                ->values()
                ->all();

            /*
            |--------------------------------------------------------------------------
            | OUTLET BARU
            |--------------------------------------------------------------------------
            */
            $newOutletIds = collect($data->outlet_ids)
                ->map(fn($id) => (int) $id)
                ->unique()
                ->sort()
                ->values()
                ->all();

            /*
            |--------------------------------------------------------------------------
            | HITUNG PERUBAHAN
            |--------------------------------------------------------------------------
            */
            $assignedOutletIds = array_values(
                array_diff($newOutletIds, $oldOutletIds)
            );

            $unassignedOutletIds = array_values(
                array_diff($oldOutletIds, $newOutletIds)
            );

            /*
            |--------------------------------------------------------------------------
            | SYNC USER OUTLET
            |--------------------------------------------------------------------------
            */
            $user->outlets()->sync($newOutletIds);

            /*
            |--------------------------------------------------------------------------
            | AUDIT ASSIGN
            |--------------------------------------------------------------------------
            */
            if (!empty($assignedOutletIds)) {

                $outlets = Outlet::query()
                    ->whereIn('id', $assignedOutletIds)
                    ->get([
                        'id',
                        'outlet_code',
                        'outlet_name',
                    ]);

                foreach ($outlets as $outlet) {
                    $this->auditLog->log(
                        action: 'ASSIGN',
                        module: 'USER_OUTLET',
                        description: "Memberikan akses outlet {$outlet->outlet_name} kepada user {$user->name}",
                        model: $user,
                        newValues: [
                            'user_id' => $user->id,
                            'user_name' => $user->name,
                            'outlet_id' => $outlet->id,
                            'outlet_code' => $outlet->outlet_code,
                            'outlet_name' => $outlet->outlet_name,
                        ],
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | AUDIT UNASSIGN
            |--------------------------------------------------------------------------
            */
            if (!empty($unassignedOutletIds)) {

                $outlets = Outlet::query()
                    ->whereIn('id', $unassignedOutletIds)
                    ->get([
                        'id',
                        'outlet_code',
                        'outlet_name',
                    ]);

                foreach ($outlets as $outlet) {
                    $this->auditLog->log(
                        action: 'UNASSIGN',
                        module: 'USER_OUTLET',
                        description: "Menghapus akses outlet {$outlet->outlet_name} dari user {$user->name}",
                        model: $user,
                        oldValues: [
                            'user_id' => $user->id,
                            'user_name' => $user->name,
                            'outlet_id' => $outlet->id,
                            'outlet_code' => $outlet->outlet_code,
                            'outlet_name' => $outlet->outlet_name,
                        ],
                    );
                }
            }

            return $user->load('outlets');
        });
    }
}
