<?php

namespace App\Domain\Tickets\Actions;

use App\Domain\Tickets\DTOs\TicketData;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class UpdateTicketAction
{
    public function __invoke(
        Ticket $ticket,
        TicketData $data
    ): Ticket {

        return DB::transaction(function () use (
            $ticket,
            $data
        ) {

            $ticket->update(
                $data->toArray()
            );

            return $ticket->refresh();
        });
    }
}