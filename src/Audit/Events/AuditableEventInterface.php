<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Audit\Events;

interface AuditableEventInterface
{
    /**
     * @return array<string, mixed>
     */
    public function auditPayload(): array;
}
