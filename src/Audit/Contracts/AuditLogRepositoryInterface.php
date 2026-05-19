<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Audit\Contracts;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Audit\DTOs\AuditLogData;
use Caydeesoft\SaasKit\Audit\Models\AuditLog;

interface AuditLogRepositoryInterface
{
    public function find(int|string $id): ?AuditLog;

    /**
     * @return Collection<int, AuditLog>
     */
    public function forSubject(string $subjectType, int|string $subjectId): Collection;

    public function create(AuditLogData $data): AuditLog;
}
