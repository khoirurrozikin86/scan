<?php

namespace App\Imports;

use App\Models\TicketQrcode;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\{
    ToCollection,
    WithHeadingRow,
    WithValidation
};

class TicketQrcodesImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {

            $noTiket = trim(
                (string) ($row['no_tiket'] ?? '')
            );

            $qrcode = trim(
                (string) ($row['qrcode'] ?? '')
            );

            $ticketType = trim(
                (string) ($row['ticket_type'] ?? '')
            );

            if (
                $noTiket === '' ||
                $qrcode === '' ||
                $ticketType === ''
            ) {
                continue;
            }

            TicketQrcode::updateOrCreate(
                [
                    'no_tiket' => $noTiket,
                ],
                [
                    'qrcode' => $qrcode,

                    'ticket_type' => $ticketType,

                    'remark' => !empty($row['remark'])
                        ? trim(
                            (string) $row['remark']
                        )
                        : null,
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            'no_tiket' => [
                'required',
            ],

            'qrcode' => [
                'required',
            ],

            'ticket_type' => [
                'required',
            ],

            'remark' => [
                'nullable',
            ],
        ];
    }
}
