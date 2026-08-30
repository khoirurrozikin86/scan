<?php

namespace App\Domain\ScanRecords\Queries;

use App\Models\ScanRecord;
use Illuminate\Database\Eloquent\Builder;

class ScanRecordTableQuery
{
    public function builder(): Builder
    {
        return ScanRecord::query()
            ->with([
                'user:id,name',
                'outlet:id,outlet_code,outlet_name',
                'ticketQrcode:id,no_tiket,qrcode,ticket_type',
            ])
            ->select([
                'id',
                'user_id',
                'outlet_id',
                'ticket_qrcode_id',
                'qrcode',
                'no_tiket',
                'ticket_type',
                'scan_method',
                'scanned_at',
                'remark',
                'created_at',
                'updated_at',
            ])
            ->orderByDesc('scanned_at');
    }
}
