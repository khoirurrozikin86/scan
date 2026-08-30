<?php

namespace App\Domain\TicketQrcodes\Actions;

use App\Domain\TicketQrcodes\DTOs\TicketQrcodeData;
use App\Models\TicketQrcode;
use Illuminate\Support\Facades\DB;

class UpdateTicketQrcodeAction
{
    public function __invoke(
        TicketQrcode $ticketQrcode,
        TicketQrcodeData $data
    ): TicketQrcode {
        return DB::transaction(function () use (
            $ticketQrcode,
            $data
        ) {
            $ticketQrcode->update(
                $data->toArray()
            );

            return $ticketQrcode->refresh();
        });
    }
}
