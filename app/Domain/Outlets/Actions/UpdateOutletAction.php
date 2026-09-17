<?php

namespace App\Domain\Outlets\Actions;

use App\Domain\Audit\Services\AuditLogService;
use App\Domain\Outlets\DTOs\OutletData;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;

class UpdateOutletAction
{
    public function __construct(
        protected AuditLogService $auditLog,
    ) {}

    public function __invoke(
        Outlet $outlet,
        OutletData $data
    ): Outlet {
        return DB::transaction(function () use ($outlet, $data) {

            // Data sebelum perubahan
            $oldValues = $outlet->getOriginal();

            // Update outlet
            $outlet->update(
                $data->toArray()
            );

            // Refresh data setelah perubahan
            $outlet->refresh();

            // Audit Log
            $this->auditLog->log(
                action: 'UPDATE',
                module: 'OUTLET',
                description: "Mengubah outlet {$outlet->name}",
                model: $outlet,
                oldValues: $oldValues,
                newValues: $outlet->toArray(),
            );

            return $outlet;
        });
    }
}
