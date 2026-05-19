<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Events;

use Caydeesoft\SaasKit\Audit\Events\AuditableEventInterface;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

final readonly class TenantCreated implements AuditableEventInterface
{
    public function __construct(public Tenant $tenant)
    {
    }

    public function auditPayload(): array
    {
        return [
            'event' => 'tenant.created',
            'subject_type' => $this->tenant::class,
            'subject_id' => (string) $this->tenant->getKey(),
            'metadata' => ['name' => $this->tenant->name],
        ];
    }
}
