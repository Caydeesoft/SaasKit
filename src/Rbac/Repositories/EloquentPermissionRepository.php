<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\Repositories;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Rbac\Contracts\PermissionRepositoryInterface;
use Caydeesoft\SaasKit\Rbac\DTOs\PermissionData;
use Caydeesoft\SaasKit\Rbac\Models\Permission;

final class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    public function find(int|string $id): ?Permission
    {
        return Permission::query()->find($id);
    }

    public function findByKey(string $key, string $guardName = 'web'): ?Permission
    {
        return Permission::query()
            ->where('key', $key)
            ->where('guard_name', $guardName)
            ->first();
    }

    public function all(): Collection
    {
        return Permission::query()->orderBy('name')->get();
    }

    public function create(PermissionData $data): Permission
    {
        return Permission::query()->create($data->toArray());
    }
}
