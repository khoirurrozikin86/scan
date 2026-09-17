<?php

namespace App\Domain\Audit\Actions;

use App\Domain\Audit\DTOs\AuditLogData;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class CreateAuditLogAction
{
    public function __invoke(
        AuditLogData $data
    ): AuditLog {
        return DB::transaction(
            fn() => AuditLog::create(
                $data->toArray()
            )
        );
    }
}
