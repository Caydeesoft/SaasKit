<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Audit\Listeners;

use Caydeesoft\SaasKit\Audit\Contracts\AuditServiceInterface;
use Caydeesoft\SaasKit\Audit\Events\AuditableEventInterface;

final class RecordAuditLog
{
    public function __construct(
        private readonly AuditServiceInterface $audit,
    ) {
    }

    /**
     * @param array<int, mixed> $payload
     */
    public function handle(string $eventName, array $payload): void
    {
        $event = $payload[0] ?? null;

        if (! $event instanceof AuditableEventInterface) {
            return;
        }

        $this->audit->recordEvent($event);
    }
}
