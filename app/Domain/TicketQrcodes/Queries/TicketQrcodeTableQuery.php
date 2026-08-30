<?php

namespace App\Domain\TicketQrcodes\Queries;

use App\Models\TicketQrcode;
use Illuminate\Database\Eloquent\Builder;

class TicketQrcodeTableQuery
{
    public function builder(): Builder
    {
        return TicketQrcode::query()
            ->select([
                'id',
                'no_tiket',
                'qrcode',
                'ticket_type',
                'remark',
                'created_at',
                'updated_at',
            ])
            ->orderByDesc('created_at');
    }
}
