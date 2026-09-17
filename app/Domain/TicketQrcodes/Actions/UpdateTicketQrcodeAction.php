<?php

namespace App\Domain\TicketQrcodes\Actions;

use App\Domain\Audit\Services\AuditLogService;
use App\Domain\TicketQrcodes\DTOs\TicketQrcodeData;
use App\Models\TicketQrcode;
use Illuminate\Support\Facades\DB;

class UpdateTicketQrcodeAction
{
    public function __construct(
        protected AuditLogService $auditLog,
    ) {}

    public function __invoke(
        TicketQrcode $ticketQrcode,
        TicketQrcodeData $data
    ): TicketQrcode {
        return DB::transaction(function () use (
            $ticketQrcode,
            $data
        ) {
            // Data sebelum perubahan
            $oldValues = $ticketQrcode->getOriginal();

            // Update Ticket QRCode
            $ticketQrcode->update(
                $data->toArray()
            );

            // Refresh untuk mendapatkan data terbaru
            $ticketQrcode->refresh();

            // Catat Audit Log
            $this->auditLog->log(
                action: 'UPDATE',
                module: 'TICKET_QRCODE',
                description: "Mengubah Ticket QRCode {$ticketQrcode->no_tiket}",
                model: $ticketQrcode,
                oldValues: $oldValues,
                newValues: $ticketQrcode->toArray(),
            );

            return $ticketQrcode;
        });
    }
}
