<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize};

class BulansExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(protected Builder $query) {}

    public function query()
    {
        return $this->query->clone();
    }

    public function headings(): array
    {
        return ['ID Bulan', 'Bulan', 'Updated At'];
    }

    public function map($row): array
    {
        return [
            $row->id_bulan,
            $row->bulan,
            optional($row->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
