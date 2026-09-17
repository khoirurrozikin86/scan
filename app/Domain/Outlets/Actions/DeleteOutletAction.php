<?php

namespace App\Domain\Outlets\Actions;

use App\Domain\Audit\Services\AuditLogService;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;

class DeleteOutletAction
{
    public function __construct(
        protected AuditLogService $auditLog,
    ) {}

    public function __invoke(Outlet $outlet): void
    {
        DB::transaction(function () use ($outlet) {

            // Simpan data sebelum dihapus
            $oldValues = $outlet->toArray();

            // Hapus outlet
            $outlet->delete();

            // Audit Log
            $this->auditLog->log(
                action: 'DELETE',
                module: 'OUTLET',
                description: "Menghapus outlet {$outlet->name}",
                model: $outlet,
                oldValues: $oldValues,
            );
        });
    }
}
