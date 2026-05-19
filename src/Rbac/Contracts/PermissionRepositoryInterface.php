<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\Contracts;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Rbac\DTOs\PermissionData;
use Caydeesoft\SaasKit\Rbac\Models\Permission;

interface PermissionRepositoryInterface
{
    public function find(int|string $id): ?Permission;

    public function findByKey(string $key, string $guardName = 'web'): ?Permission;

    /**
     * @return Collection<int, Permission>
     */
    public function all(): Collection;

    public function create(PermissionData $data): Permission;
}
