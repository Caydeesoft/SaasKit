<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\Contracts;

use Caydeesoft\SaasKit\Rbac\DTOs\PermissionData;
use Caydeesoft\SaasKit\Rbac\DTOs\RoleData;
use Caydeesoft\SaasKit\Rbac\Models\Permission;
use Caydeesoft\SaasKit\Rbac\Models\Role;

interface RbacServiceInterface
{
    public function createRole(RoleData $data): Role;

    public function createPermission(PermissionData $data): Permission;

    public function assignPermissionToRole(int|string $roleId, int|string $permissionId): void;

    public function assignRoleToUser(
        int|string $userId,
        int|string $roleId,
        ?string $userType = null,
    ): void;

    public function userHasPermission(
        int|string $userId,
        string $permissionKey,
        ?string $userType = null,
        string $guardName = 'web',
    ): bool;
}
