<?php

namespace App\Domain\TicketQrcodes\Actions;

use App\Domain\Audit\Services\AuditLogService;
use App\Models\TicketQrcode;
use Illuminate\Support\Facades\DB;

class DeleteTicketQrcodeAction
{
    public function __construct(
        protected AuditLogService $auditLog,
    ) {}

    public function __invoke(
        TicketQrcode $ticketQrcode
    ): void {
        DB::transaction(function () use ($ticketQrcode) {
            // Simpan data sebelum dihapus
            $oldValues = $ticketQrcode->toArray();

            $ticketId = $ticketQrcode->id;
            $ticketNumber = $ticketQrcode->no_tiket;
            $qrcode = $ticketQrcode->qrcode;

            // Hapus Ticket QRCode
            $ticketQrcode->delete();

            // Catat Audit Log
            $this->auditLog->log(
                action: 'DELETE',
                module: 'TICKET_QRCODE',
                description: "Menghapus Ticket QRCode {$ticketNumber}",
                model: $ticketQrcode,
                oldValues: $oldValues,
            );
        });
    }
}
