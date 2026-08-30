<?php

namespace App\Domain\ScanRecords\Actions;

use App\Domain\ScanRecords\DTOs\ScanRecordData;
use App\Models\ScanRecord;

class UpdateScanRecordAction
{
    public function __invoke(
        ScanRecord $scanRecord,
        ScanRecordData $data
    ): ScanRecord {
        $scanRecord->update(
            $data->toArray()
        );

        return $scanRecord->refresh();
    }
}
