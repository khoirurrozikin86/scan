<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize};

class PaymentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(protected Builder $query) {}

    public function query()
    {
        // Builder yang dikirim (dari PaymentTableQuery) sudah lengkap dgn alias lokasi_server
        return $this->query->clone();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Paid At',
            'Amount',
            'No Tagihan',
            'ID Pelanggan',
            'Nama Pelanggan',
            'Method',
            'Ref No',
            'Lokasi Server', // ← tambahan
            'User',
        ];
    }

    public function map($r): array
    {
        $fmt = fn($v, $format) => $v ? Carbon::parse($v)->format($format) : null;

        return [
            $r->id,
            $fmt($r->paid_at, 'Y-m-d'),
            (float) $r->amount,
            $r->no_tagihan,
            $r->id_pelanggan,
            $r->nama_pelanggan,
            $r->method,
            $r->ref_no,
            $r->lokasi_server, // ← dari alias di PaymentTableQuery
            $r->user_name,
        ];
    }
}
