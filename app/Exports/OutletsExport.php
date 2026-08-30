<?php

namespace App\Exports;

use App\Models\Outlet;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
};

class OutletsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    public function collection()
    {
        return Outlet::query()
            ->orderByDesc('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Outlet Code',
            'Outlet Name',
            'Outlet Type',
            'Status',
            'Created At',
            'Updated At',
        ];
    }

    public function map($outlet): array
    {
        static $no = 0;

        return [
            ++$no,
            $outlet->outlet_code,
            $outlet->outlet_name,
            $outlet->outlet_type,
            $outlet->is_active
                ? 'Active'
                : 'Non Active',
            optional($outlet->created_at)
                ->format('Y-m-d H:i:s'),
            optional($outlet->updated_at)
                ->format('Y-m-d H:i:s'),
        ];
    }
}
