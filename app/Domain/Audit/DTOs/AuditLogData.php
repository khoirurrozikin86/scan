<?php

namespace App\Domain\Audit\DTOs;

use Illuminate\Database\Eloquent\Model;

class AuditLogData
{
    public function __construct(
        public readonly ?int $userId,
        public readonly string $action,
        public readonly string $module,
        public readonly string $description,
        public readonly ?Model $model = null,
        public readonly ?array $oldValues = null,
        public readonly ?array $newValues = null,
        public readonly ?string $ipAddress = null,
        public readonly ?string $method = null,
        public readonly ?string $url = null,
        public readonly ?string $userAgent = null,
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,

            'action' => $this->action,

            'module' => $this->module,

            'description' => $this->description,

            'auditable_type' => $this->model?->getMorphClass(),

            'auditable_id' => $this->model?->getKey(),

            'old_values' => $this->oldValues,

            'new_values' => $this->newValues,

            'ip_address' => $this->ipAddress,

            'method' => $this->method,

            'url' => $this->url,

            'user_agent' => $this->userAgent,
        ];
    }
}
