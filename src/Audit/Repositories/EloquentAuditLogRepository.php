<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Audit\Repositories;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Audit\Contracts\AuditLogRepositoryInterface;
use Caydeesoft\SaasKit\Audit\DTOs\AuditLogData;
use Caydeesoft\SaasKit\Audit\Models\AuditLog;

final class EloquentAuditLogRepository implements AuditLogRepositoryInterface
{
    public function find(int|string $id): ?AuditLog
    {
        return AuditLog::query()->find($id);
    }

    public function forSubject(string $subjectType, int|string $subjectId): Collection
    {
        return AuditLog::query()
            ->where('subject_type', $subjectType)
            ->where('subject_id', (string) $subjectId)
            ->latest('occurred_at')
            ->get();
    }

    public function create(AuditLogData $data): AuditLog
    {
        return AuditLog::query()->create($data->toArray());
    }
}
