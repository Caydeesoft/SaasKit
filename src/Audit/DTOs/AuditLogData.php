<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Audit\DTOs;

use DateTimeInterface;

final readonly class AuditLogData
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $event,
        public ?string $actorType = null,
        public int|string|null $actorId = null,
        public ?string $subjectType = null,
        public int|string|null $subjectId = null,
        public int|string|null $tenantId = null,
        public array $metadata = [],
        public ?DateTimeInterface $occurredAt = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'event' => $this->event,
            'actor_type' => $this->actorType,
            'actor_id' => $this->actorId !== null ? (string) $this->actorId : null,
            'subject_type' => $this->subjectType,
            'subject_id' => $this->subjectId !== null ? (string) $this->subjectId : null,
            'tenant_id' => $this->tenantId !== null ? (string) $this->tenantId : null,
            'metadata' => $this->metadata,
            'occurred_at' => $this->occurredAt ?? now(),
        ];
    }
}
