<?php

namespace App\Domain\ScanRecords\Services;

use App\Domain\ScanRecords\Actions\{
    CreateScanRecordAction,
    UpdateScanRecordAction,
    DeleteScanRecordAction
};

use App\Domain\ScanRecords\DTOs\ScanRecordData;
use App\Models\ScanRecord;

class ScanRecordService
{
    public function __construct(
        protected CreateScanRecordAction $create,
        protected UpdateScanRecordAction $update,
        protected DeleteScanRecordAction $delete,
    ) {}

    public function create(array $payload): ScanRecord
    {
        return ($this->create)(
            ScanRecordData::fromArray($payload)
        );
    }

    public function update(
        ScanRecord $scanRecord,
        array $payload
    ): ScanRecord {
        return ($this->update)(
            $scanRecord,
            ScanRecordData::fromArray($payload)
        );
    }

    public function delete(
        ScanRecord $scanRecord
    ): void {
        ($this->delete)($scanRecord);
    }
}
