<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Caydeesoft\SaasKit\Rbac\Contracts\PermissionRepositoryInterface;
use Caydeesoft\SaasKit\Rbac\Contracts\RbacServiceInterface;
use Caydeesoft\SaasKit\Rbac\Contracts\RoleRepositoryInterface;
use Caydeesoft\SaasKit\Rbac\DTOs\PermissionData;
use Caydeesoft\SaasKit\Rbac\DTOs\RoleData;
use Caydeesoft\SaasKit\Rbac\Events\RoleAssigned;
use Caydeesoft\SaasKit\Rbac\Models\Permission;
use Caydeesoft\SaasKit\Rbac\Models\Role;

final class RbacService implements RbacServiceInterface
{
    public function __construct(
        private readonly RoleRepositoryInterface $roles,
        private readonly PermissionRepositoryInterface $permissions,
        private readonly Dispatcher $events,
    ) {
    }

    public function createRole(RoleData $data): Role
    {
        return $this->roles->create($data);
    }

    public function createPermission(PermissionData $data): Permission
    {
        return $this->permissions->create($data);
    }

    public function assignPermissionToRole(int|string $roleId, int|string $permissionId): void
    {
        DB::table('saas_kit_role_permissions')->updateOrInsert(
            ['role_id' => $roleId, 'permission_id' => $permissionId],
            ['created_at' => now(), 'updated_at' => now()],
        );
    }

    public function assignRoleToUser(
        int|string $userId,
        int|string $roleId,
        ?string $userType = null,
    ): void {
        $modelType = $userType ?? (string) config('saas-kit.users.model');

        DB::table('saas_kit_model_has_roles')->updateOrInsert(
            ['role_id' => $roleId, 'model_type' => $modelType, 'model_id' => (string) $userId],
            ['created_at' => now(), 'updated_at' => now()],
        );

        $this->events->dispatch(new RoleAssigned((string) $userId, $modelType, (string) $roleId));
    }

    public function userHasPermission(
        int|string $userId,
        string $permissionKey,
        ?string $userType = null,
        string $guardName = 'web',
    ): bool {
        $modelType = $userType ?? (string) config('saas-kit.users.model');

        return DB::table('saas_kit_model_has_roles as mr')
            ->join('saas_kit_role_permissions as rp', 'rp.role_id', '=', 'mr.role_id')
            ->join('saas_kit_permissions as p', 'p.id', '=', 'rp.permission_id')
            ->where('mr.model_type', $modelType)
            ->where('mr.model_id', (string) $userId)
            ->where('p.key', $permissionKey)
            ->where('p.guard_name', $guardName)
            ->exists();
    }
}
