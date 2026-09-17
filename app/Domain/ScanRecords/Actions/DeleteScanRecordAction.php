<?php

namespace App\Domain\ScanRecords\Actions;

use App\Domain\Audit\Services\AuditLogService;
use App\Models\ScanRecord;

class DeleteScanRecordAction
{
    public function __construct(
        protected AuditLogService $auditLog,
    ) {}

    public function __invoke(
        ScanRecord $scanRecord
    ): void {

        // Simpan data sebelum dihapus
        $oldValues = $scanRecord->toArray();

        // Simpan informasi untuk description
        $scanRecordId = $scanRecord->id;

        // Hapus record
        $scanRecord->delete();

        // Audit Log
        $this->auditLog->log(
            action: 'DELETE',
            module: 'SCAN_RECORD',
            description: "Menghapus Scan Record #{$scanRecordId}",
            model: $scanRecord,
            oldValues: $oldValues,
        );
    }
}
