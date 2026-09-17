<?php

namespace App\Domain\ScanRecords\Actions;

use App\Domain\Audit\Services\AuditLogService;
use App\Domain\ScanRecords\DTOs\ScanRecordData;
use App\Models\ScanRecord;

class UpdateScanRecordAction
{
    public function __construct(
        protected AuditLogService $auditLog,
    ) {}

    public function __invoke(
        ScanRecord $scanRecord,
        ScanRecordData $data
    ): ScanRecord {

        // Data sebelum perubahan
        $oldValues = $scanRecord->getOriginal();

        // Update
        $scanRecord->update(
            $data->toArray()
        );

        // Data setelah perubahan
        $scanRecord->refresh();

        // Audit Log
        $this->auditLog->log(
            action: 'UPDATE',
            module: 'SCAN_RECORD',
            description: "Mengubah Scan Record #{$scanRecord->id}",
            model: $scanRecord,
            oldValues: $oldValues,
            newValues: $scanRecord->toArray(),
        );

        return $scanRecord;
    }
}
