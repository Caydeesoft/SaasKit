<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\Contracts;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Rbac\DTOs\RoleData;
use Caydeesoft\SaasKit\Rbac\Models\Role;

interface RoleRepositoryInterface
{
    public function find(int|string $id): ?Role;

    public function findByKey(string $key, string $guardName = 'web'): ?Role;

    /**
     * @return Collection<int, Role>
     */
    public function all(): Collection;

    public function create(RoleData $data): Role;
}
