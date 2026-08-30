<?php

namespace App\Domain\ScanRecords\Actions;

use App\Models\ScanRecord;

class DeleteScanRecordAction
{
    public function __invoke(
        ScanRecord $scanRecord
    ): void {
        $scanRecord->delete();
    }
}
