<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize};

class TagihansExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(protected Builder $query) {}

    public function query()
    {
        // Builder yang kamu kirim (dari TagihanTableQuery) sudah lengkap dgn join + alias lokasi_server
        return $this->query->clone();
    }

    public function headings(): array
    {
        return [
            'No Tagihan',
            'Bulan',
            'Tahun',
            'ID Pelanggan',
            'Nama Pelanggan',
            'Jumlah',
            'Status',
            'Tgl Bayar',
            'Lokasi Server',   // ← tambahan
            'Updated At',
        ];
    }

    public function map($r): array
    {
        $fmt = fn($v, $format) => $v ? Carbon::parse($v)->format($format) : null;

        return [
            $r->no_tagihan,
            $r->nama_bulan,
            $r->tahun,
            $r->id_pelanggan,
            $r->nama_pelanggan,
            (float) $r->jumlah_tagihan,
            $r->status,
            $fmt($r->tgl_bayar, 'Y-m-d'),
            $r->lokasi_server, // ← dari alias di TagihanTableQuery
            $fmt($r->updated_at, 'Y-m-d H:i:s'),
        ];
    }
}
