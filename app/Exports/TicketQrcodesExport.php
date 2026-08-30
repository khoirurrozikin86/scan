<?php

namespace App\Exports;

use App\Models\TicketQrcode;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketQrcodesExport implements
    FromCollection,
    WithHeadings
{
    public function collection()
    {
        return TicketQrcode::query()
            ->orderBy('no_tiket', 'desc')
            ->get([
                'no_tiket',
                'qrcode',
                'ticket_type',
                'remark',
            ]);
    }

    public function headings(): array
    {
        return [
            'no_tiket',
            'qrcode',
            'ticket_type',
            'remark',
        ];
    }
}
