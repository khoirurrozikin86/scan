<?php

namespace App\Domain\Tickets\Services;

use App\Domain\Tickets\Actions\{
    CreateTicketAction,
    UpdateTicketAction,
    DeleteTicketAction,
    UpdateTicketStatusAction
};

use App\Domain\Tickets\DTOs\TicketData;
use App\Models\Ticket;

class TicketService
{
    public function __construct(
        protected CreateTicketAction $create,
        protected UpdateTicketAction $update,
        protected DeleteTicketAction $delete,
        protected UpdateTicketStatusAction $updateStatus,
    ) {
    }

    public function create(array $payload): Ticket
    {
        return ($this->create)(
            TicketData::fromArray($payload)
        );
    }

    public function update(
        Ticket $ticket,
        array $payload
    ): Ticket {
        return ($this->update)(
            $ticket,
            TicketData::fromArray($payload)
        );
    }

    public function delete(Ticket $ticket): void
    {
        ($this->delete)($ticket);
    }

    public function updateStatus(
        Ticket $ticket,
        string $status
    ): Ticket {

        return ($this->updateStatus)(
            $ticket,
            $status
        );
    }
}