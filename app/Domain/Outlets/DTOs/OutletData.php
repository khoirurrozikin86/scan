<?php

namespace App\Domain\Outlets\DTOs;

class OutletData
{
    public function __construct(
        public string $outlet_code,
        public string $outlet_name,
        public string $outlet_type,
        public bool $is_active = true,
        public bool $is_camera_enabled = true,
        public bool $is_scanner_enabled = true,
        public ?string $remark = null,
    ) {}

    public static function fromArray(array $a): self
    {
        return new self(
            outlet_code: trim((string) ($a['outlet_code'] ?? '')),

            outlet_name: trim(
                (string) ($a['outlet_name'] ?? '')
            ),

            outlet_type: trim(
                (string) ($a['outlet_type'] ?? '')
            ),

            is_active: filter_var(
                $a['is_active'] ?? true,
                FILTER_VALIDATE_BOOLEAN
            ),
            is_camera_enabled: filter_var(
                $a['is_camera_enabled'] ?? true,
                FILTER_VALIDATE_BOOLEAN
            ),
            is_scanner_enabled: filter_var(
                $a['is_scanner_enabled'] ?? true,
                FILTER_VALIDATE_BOOLEAN
            ),
            remark: trim((string) ($a['remark'] ?? null)),
        );
    }

    public function toArray(): array
    {
        return [
            'outlet_code' => $this->outlet_code,
            'outlet_name' => $this->outlet_name,
            'outlet_type' => $this->outlet_type,
            'is_active'   => $this->is_active,

            'is_camera_enabled' => $this->is_camera_enabled,
            'is_scanner_enabled' => $this->is_scanner_enabled,

            'remark' => $this->remark,
        ];
    }
}
