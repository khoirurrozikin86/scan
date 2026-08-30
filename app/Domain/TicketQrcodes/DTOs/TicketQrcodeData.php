<?php

namespace App\Domain\TicketQrcodes\DTOs;

class TicketQrcodeData
{
    public function __construct(
        public string $no_tiket,
        public string $qrcode,
        public string $ticket_type,
        public ?string $remark = null,
    ) {}

    public static function fromArray(array $a): self
    {
        return new self(
            no_tiket: trim((string) ($a['no_tiket'] ?? '')),

            qrcode: trim(
                (string) ($a['qrcode'] ?? '')
            ),

            ticket_type: trim(
                (string) ($a['ticket_type'] ?? '')
            ),

            remark: !empty($a['remark'])
                ? trim((string) $a['remark'])
                : null,
        );
    }

    public function toArray(): array
    {
        return [
            'no_tiket' => $this->no_tiket,
            'qrcode' => $this->qrcode,
            'ticket_type' => $this->ticket_type,
            'remark' => $this->remark,
        ];
    }
}
