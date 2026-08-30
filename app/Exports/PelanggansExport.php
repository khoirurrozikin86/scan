<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize};

class PelanggansExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(protected Builder $query) {}

    public function query()
    {
        return $this->query->clone();
    }

    public function headings(): array
    {
        return ['ID Pelanggan', 'Nama', 'No HP', 'Email', 'Paket', 'Server IP', 'IP Router', 'IP Parent', 'Remark1', 'Remark2', 'Remark3', 'Updated At'];
    }

    public function map($row): array
    {
        return [
            $row->id_pelanggan,
            $row->nama,
            $row->no_hp,
            $row->email,
            $row->paket_nama,
            $row->server_ip,
            $row->ip_router,
            $row->ip_parent_router,
            $row->remark1,
            $row->remark2,
            $row->remark3,
            optional($row->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
