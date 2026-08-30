<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting};
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PaketsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    public function __construct(protected Builder $query) {}

    public function query()
    {
        return $this->query->clone(); // jaga builder asli
    }

    public function headings(): array
    {
        return [
            'ID Paket',
            'Nama',
            'Harga',
            'Kecepatan',
            'Durasi (hari)',
            'Remark1',
            'Remark2',
            'Remark3',
            'Updated At'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id_paket,
            $row->nama,
            (float) $row->harga,
            $row->kecepatan,
            (int) $row->durasi,
            $row->remark1,
            $row->remark2,
            $row->remark3,
            optional($row->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_00, // Harga
            'E' => NumberFormat::FORMAT_NUMBER,    // Durasi
        ];
    }
}
