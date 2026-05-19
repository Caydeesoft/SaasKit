<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Audit\Services;

use Illuminate\Support\Facades\Auth;
use Caydeesoft\SaasKit\Audit\Contracts\AuditLogRepositoryInterface;
use Caydeesoft\SaasKit\Audit\Contracts\AuditServiceInterface;
use Caydeesoft\SaasKit\Audit\DTOs\AuditLogData;
use Caydeesoft\SaasKit\Audit\Events\AuditableEventInterface;
use Caydeesoft\SaasKit\Audit\Models\AuditLog;
use Caydeesoft\SaasKit\Tenancy\Services\TenantContext;

final class AuditService implements AuditServiceInterface
{
    public function __construct(
        private readonly AuditLogRepositoryInterface $auditLogs,
        private readonly TenantContext $tenantContext,
    ) {
    }

    public function record(AuditLogData $data): AuditLog
    {
        return $this->auditLogs->create($data);
    }

    public function recordEvent(AuditableEventInterface $event): AuditLog
    {
        $payload = $event->auditPayload();
        $actor = Auth::user();
        $tenant = $this->tenantContext->get();

        return $this->record(new AuditLogData(
            event: (string) $payload['event'],
            actorType: $payload['actor_type'] ?? ($actor !== null ? $actor::class : null),
            actorId: $payload['actor_id'] ?? (
                $actor !== null && method_exists($actor, 'getKey') ? (string) $actor->getKey() : null
            ),
            subjectType: $payload['subject_type'] ?? null,
            subjectId: $payload['subject_id'] ?? null,
            tenantId: $payload['tenant_id'] ?? ($tenant?->getKey()),
            metadata: (array) ($payload['metadata'] ?? []),
        ));
    }
}
