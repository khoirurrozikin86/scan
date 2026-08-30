<?php

namespace App\Domain\Tickets\Actions;

use App\Domain\Tickets\DTOs\TicketData;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class CreateTicketAction
{
    public function __invoke(TicketData $data): Ticket
    {
        return DB::transaction(function () use ($data) {

            $payload = $data->toArray();

            $payload['ticket_number'] =
                'TK-' . str_pad(
                    Ticket::count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            $payload['created_date'] = now();

            return Ticket::create($payload);
        });
    }
}