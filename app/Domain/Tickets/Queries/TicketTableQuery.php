<?php

namespace App\Domain\Tickets\Queries;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;

class TicketTableQuery
{
    public function builder(): Builder
    {
        return Ticket::query()
            ->with([
                'category',
                'creator',
                'assignedUser'
            ]);
    }
}