<?php

namespace App\Domain\ScanRecords\Actions;

use App\Domain\Audit\Services\AuditLogService;
use App\Domain\ScanRecords\DTOs\ScanRecordData;
use App\Models\ScanRecord;

class CreateScanRecordAction
{
    public function __construct(
        protected AuditLogService $auditLog,
    ) {}

    public function __invoke(
        ScanRecordData $data
    ): ScanRecord {

        $scanRecord = ScanRecord::create(
            $data->toArray()
        );

        $this->auditLog->log(
            action: 'SCAN',
            module: 'SCAN_RECORD',
            description: 'Ticket QRCode berhasil discan',
            model: $scanRecord,
            newValues: $scanRecord->toArray(),
        );

        return $scanRecord;
    }
}
