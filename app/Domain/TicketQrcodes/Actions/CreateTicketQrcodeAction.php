<?php

namespace App\Domain\TicketQrcodes\Actions;

use App\Domain\Audit\Services\AuditLogService;
use App\Domain\TicketQrcodes\DTOs\TicketQrcodeData;
use App\Models\TicketQrcode;
use Illuminate\Support\Facades\DB;

class CreateTicketQrcodeAction
{
    public function __construct(
        protected AuditLogService $auditLog,
    ) {}

    public function __invoke(
        TicketQrcodeData $data
    ): TicketQrcode {
        return DB::transaction(function () use ($data) {
            $ticket = TicketQrcode::create(
                $data->toArray()
            );

            $this->auditLog->log(
                action: 'CREATE',
                module: 'TICKET_QRCODE',
                description: "Membuat Ticket QRCode {$ticket->no_tiket}",
                model: $ticket,
                newValues: $ticket->toArray(),
            );

            return $ticket;
        });
    }
}
