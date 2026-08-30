<?php

namespace App\Domain\TicketQrcodes\Services;

use App\Domain\TicketQrcodes\Actions\{
    CreateTicketQrcodeAction,
    UpdateTicketQrcodeAction,
    DeleteTicketQrcodeAction
};

use App\Domain\TicketQrcodes\DTOs\TicketQrcodeData;
use App\Models\TicketQrcode;

class TicketQrcodeService
{
    public function __construct(
        protected CreateTicketQrcodeAction $create,
        protected UpdateTicketQrcodeAction $update,
        protected DeleteTicketQrcodeAction $delete,
    ) {}

    public function create(array $payload): TicketQrcode
    {
        return ($this->create)(
            TicketQrcodeData::fromArray($payload)
        );
    }

    public function update(
        TicketQrcode $ticketQrcode,
        array $payload
    ): TicketQrcode {
        return ($this->update)(
            $ticketQrcode,
            TicketQrcodeData::fromArray($payload)
        );
    }

    public function delete(
        TicketQrcode $ticketQrcode
    ): void {
        ($this->delete)($ticketQrcode);
    }
}
