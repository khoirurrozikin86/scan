<?php

namespace App\Exports;

use App\Models\ScanRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ScanOutletSummaryExport implements FromCollection, WithHeadings,  WithTitle
{
    public function __construct(
        protected string $dateFrom,
        protected string $dateTo,
        protected ?int $userId = null,
        protected ?int $outletId = null,
        protected ?string $outletType = null,
        protected array $allowedOutletIds = [],
    ) {}

    public function title(): string
    {
        return 'Rekap per Outlet';
    }

    public function collection(): Collection
    {
        $query = ScanRecord::query()
            ->select(
                'outlet_id',
            )
            ->selectRaw(
                'COUNT(DISTINCT ticket_qrcode_id) as total_tiket'
            )
            ->with([
                'outlet:id,outlet_code,outlet_name',
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
            );

        /*
        |--------------------------------------------------------------------------
        | BATASI OUTLET SESUAI AKSES USER
        |--------------------------------------------------------------------------
        */

        if (!empty($this->allowedOutletIds)) {
            $query->whereIn(
                'outlet_id',
                $this->allowedOutletIds
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER USER
        |--------------------------------------------------------------------------
        */

        if ($this->userId !== null) {
            $query->where(
                'user_id',
                $this->userId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER OUTLET
        |--------------------------------------------------------------------------
        */

        if ($this->outletId !== null) {
            $query->where(
                'outlet_id',
                $this->outletId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER OUTLET TYPE
        |--------------------------------------------------------------------------
        */

        if (
            $this->outletType !== null &&
            $this->outletType !== ''
        ) {
            $query->whereHas(
                'outlet',
                function (Builder $outletQuery) {
                    $outletQuery->where(
                        'outlet_type',
                        $this->outletType
                    );
                }
            );
        }

        return $query
            ->groupBy('outlet_id')
            ->orderByDesc('total_tiket')
            ->get()
            ->map(function ($row) {
                return [
                    $row->outlet?->outlet_code ?? '-',
                    $row->outlet?->outlet_name ?? '-',
                    (int) $row->total_tiket,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Outlet',
            'Nama Outlet',
            'Tiket Unik',
        ];
    }
}
