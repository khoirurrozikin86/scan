<?php

namespace App\Domain\TicketQrcodes\Actions;

use App\Domain\TicketQrcodes\DTOs\TicketQrcodeData;
use App\Models\TicketQrcode;
use Illuminate\Support\Facades\DB;

class CreateTicketQrcodeAction
{
    public function __invoke(
        TicketQrcodeData $data
    ): TicketQrcode {
        return DB::transaction(
            fn() => TicketQrcode::create(
                $data->toArray()
            )
        );
    }
}
