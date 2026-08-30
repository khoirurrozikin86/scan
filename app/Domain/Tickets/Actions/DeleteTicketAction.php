<?php

namespace App\Domain\Tickets\Actions;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class DeleteTicketAction
{
    public function __invoke(Ticket $ticket): void
    {
        DB::transaction(
            fn() => $ticket->delete()
        );
    }
}