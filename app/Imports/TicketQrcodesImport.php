<?php

namespace App\Imports;

use App\Models\TicketQrcode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TicketQrcodesImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

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

                $remark = isset($row['remark'])
                    ? trim((string) $row['remark'])
                    : null;


                /*
                 * Cek no tiket
                 */
                if (
                    TicketQrcode::where(
                        'no_tiket',
                        $noTiket
                    )->exists()
                ) {
                    throw new \Exception(
                        "No tiket {$noTiket} sudah terdaftar."
                    );
                }


                /*
                 * Cek QR Code
                 */
                if (
                    TicketQrcode::where(
                        'qrcode',
                        $qrcode
                    )->exists()
                ) {
                    throw new \Exception(
                        "QR Code {$qrcode} sudah terdaftar."
                    );
                }


                /*
                 * Insert
                 */
                TicketQrcode::create([
                    'no_tiket'    => $noTiket,
                    'qrcode'      => $qrcode,
                    'ticket_type' => $ticketType,
                    'remark'      => $remark,
                ]);
            }
        });
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
