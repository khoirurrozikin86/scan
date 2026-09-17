<?php

namespace App\Domain\Outlets\Actions;

use App\Domain\Audit\Services\AuditLogService;
use App\Domain\Outlets\DTOs\OutletData;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;

class CreateOutletAction
{
    public function __construct(
        protected AuditLogService $auditLog,
    ) {}

    public function __invoke(OutletData $data): Outlet
    {
        return DB::transaction(function () use ($data) {

            // Create outlet
            $outlet = Outlet::create(
                $data->toArray()
            );

            // Audit Log
            $this->auditLog->log(
                action: 'CREATE',
                module: 'OUTLET',
                description: "Membuat outlet {$outlet->name}",
                model: $outlet,
                newValues: $outlet->toArray(),
            );

            return $outlet;
        });
    }
}
