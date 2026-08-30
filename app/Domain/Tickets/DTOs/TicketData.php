<?php

namespace App\Domain\Tickets\DTOs;

class TicketData
{
    public function __construct(
        public string $title,
        public int $category_id,
        public int $created_by,
        public ?int $assigned_user_id,
        public string $priority,
        public string $status,
        public ?string $description,
        public ?string $notes,
    ) {}

    public static function fromArray(array $a): self
    {
        return new self(
            title: trim((string)($a['title'] ?? '')),
            category_id: (int)($a['category_id'] ?? 0),
            created_by: (int)($a['created_by'] ?? auth()->id()),
            assigned_user_id: !empty($a['assigned_user_id'])
                ? (int)$a['assigned_user_id']
                : null,
            priority: $a['priority'] ?? 'Medium',
            status: $a['status'] ?? 'Open',
            description: $a['description'] ?? null,
            notes: $a['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'category_id' => $this->category_id,
            'created_by' => $this->created_by,
            'assigned_user_id' => $this->assigned_user_id,
            'priority' => $this->priority,
            'status' => $this->status,
            'description' => $this->description,
            'notes' => $this->notes,
        ];
    }
}