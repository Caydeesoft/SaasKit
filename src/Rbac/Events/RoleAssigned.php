<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\Events;

use Caydeesoft\SaasKit\Audit\Events\AuditableEventInterface;

final readonly class RoleAssigned implements AuditableEventInterface
{
    public function __construct(
        public string $modelId,
        public string $modelType,
        public string $roleId,
    ) {
    }

    public function auditPayload(): array
    {
        return [
            'event' => 'rbac.role_assigned',
            'subject_type' => $this->modelType,
            'subject_id' => $this->modelId,
            'metadata' => ['role_id' => $this->roleId],
        ];
    }
}
