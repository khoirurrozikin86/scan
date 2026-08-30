<?php

namespace App\Domain\ScanRecords\Actions;

use App\Domain\ScanRecords\DTOs\ScanRecordData;
use App\Models\ScanRecord;

class CreateScanRecordAction
{
    public function __invoke(
        ScanRecordData $data
    ): ScanRecord {
        return ScanRecord::create(
            $data->toArray()
        );
    }
}
