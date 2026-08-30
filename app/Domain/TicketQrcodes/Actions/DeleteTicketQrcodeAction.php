<?php

namespace App\Domain\TicketQrcodes\Actions;

use App\Models\TicketQrcode;
use Illuminate\Support\Facades\DB;

class DeleteTicketQrcodeAction
{
    public function __invoke(
        TicketQrcode $ticketQrcode
    ): void {
        DB::transaction(
            fn() => $ticketQrcode->delete()
        );
    }
}
