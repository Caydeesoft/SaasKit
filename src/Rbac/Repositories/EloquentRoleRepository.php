<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\Repositories;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Rbac\Contracts\RoleRepositoryInterface;
use Caydeesoft\SaasKit\Rbac\DTOs\RoleData;
use Caydeesoft\SaasKit\Rbac\Models\Role;

final class EloquentRoleRepository implements RoleRepositoryInterface
{
    public function find(int|string $id): ?Role
    {
        return Role::query()->find($id);
    }

    public function findByKey(string $key, string $guardName = 'web'): ?Role
    {
        return Role::query()
            ->where('key', $key)
            ->where('guard_name', $guardName)
            ->first();
    }

    public function all(): Collection
    {
        return Role::query()->orderBy('name')->get();
    }

    public function create(RoleData $data): Role
    {
        return Role::query()->create($data->toArray());
    }
}
