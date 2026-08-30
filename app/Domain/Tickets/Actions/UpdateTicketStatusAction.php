<?php

namespace App\Domain\Tickets\Actions;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class UpdateTicketStatusAction
{
    public function __invoke(
        Ticket $ticket,
        string $status
    ): Ticket {

        return DB::transaction(function () use ($ticket, $status) {

            $ticket->update([
                'status' => $status
            ]);

            return $ticket->refresh();
        });
    }
}