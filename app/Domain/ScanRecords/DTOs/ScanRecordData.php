<?php

namespace App\Domain\ScanRecords\DTOs;

class ScanRecordData
{
    public function __construct(
        public int $user_id,
        public int $outlet_id,
        public int $ticket_qrcode_id,
        public string $qrcode,
        public string $no_tiket,
        public ?string $ticket_type = null,
        public string $scan_method = 'camera',
        public ?string $scanned_at = null,
        public ?string $remark = null,
    ) {}

    public static function fromArray(array $a): self
    {
        return new self(
            user_id: (int) ($a['user_id'] ?? 0),

            outlet_id: (int) ($a['outlet_id'] ?? 0),

            ticket_qrcode_id: (int) (
                $a['ticket_qrcode_id'] ?? 0
            ),

            qrcode: trim(
                (string) ($a['qrcode'] ?? '')
            ),

            no_tiket: trim(
                (string) ($a['no_tiket'] ?? '')
            ),

            ticket_type: isset($a['ticket_type'])
                ? trim((string) $a['ticket_type'])
                : null,

            scan_method: trim(
                (string) ($a['scan_method'] ?? 'camera')
            ),

            scanned_at: $a['scanned_at'] ?? null,

            remark: isset($a['remark'])
                ? trim((string) $a['remark'])
                : null,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'outlet_id' => $this->outlet_id,
            'ticket_qrcode_id' => $this->ticket_qrcode_id,
            'qrcode' => $this->qrcode,
            'no_tiket' => $this->no_tiket,
            'ticket_type' => $this->ticket_type,
            'scan_method' => $this->scan_method,
            'scanned_at' => $this->scanned_at,
            'remark' => $this->remark,
        ];
    }
}
