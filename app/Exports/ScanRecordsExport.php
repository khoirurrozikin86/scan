<?php

namespace App\Exports;

use App\Models\ScanRecord;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScanRecordsExport implements
    FromQuery,
    WithHeadings,
    WithMapping
{
    public function __construct(
        protected string $dateFrom,
        protected string $dateTo,
    ) {}

    public function query()
    {
        return ScanRecord::query()

            ->with([
                'user',
                'outlet',
            ])

            ->whereDate(
                'scanned_at',
                '>=',
                $this->dateFrom
            )

            ->whereDate(
                'scanned_at',
                '<=',
                $this->dateTo
            )

            ->orderByDesc('scanned_at');
    }

    public function headings(): array
    {
        return [
            'No Tiket',
            'QR Code',
            'Ticket Type',
            'Operator',
            'Outlet',
            'Outlet Type',
            'Method',
            'Scanned At',
        ];
    }

    public function map($scan): array
    {
        return [

            $scan->no_tiket ?? '-',

            $scan->qrcode ?? '-',

            $scan->ticket_type ?? '-',

            $scan->user?->name ?? '-',

            $scan->outlet?->outlet_name ?? '-',

            $scan->outlet?->outlet_type ?? '-',

            ucfirst($scan->scan_method ?? '-'),

            $scan->scanned_at
                ? $scan->scanned_at->format('d-m-Y H:i:s')
                : '-',
        ];
    }
}
