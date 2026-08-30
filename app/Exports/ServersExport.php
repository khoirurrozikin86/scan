<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize};

class ServersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(protected Builder $query) {}

    public function query()
    {
        return $this->query->clone();
    }

    public function headings(): array
    {
        return ['IP', 'User', 'Lokasi', 'No INT', 'Mikrotik', 'Remark1', 'Remark2', 'Remark3', 'Updated At'];
    }

    public function map($row): array
    {
        return [
            $row->ip,
            $row->user,
            $row->lokasi,
            $row->no_int,
            $row->mikrotik,
            $row->remark1,
            $row->remark2,
            $row->remark3,
            optional($row->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
