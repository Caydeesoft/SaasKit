<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Rbac\Contracts\PermissionRepositoryInterface;
use Caydeesoft\SaasKit\Rbac\Contracts\RbacServiceInterface;
use Caydeesoft\SaasKit\Rbac\Contracts\RoleRepositoryInterface;
use Caydeesoft\SaasKit\Rbac\Repositories\EloquentPermissionRepository;
use Caydeesoft\SaasKit\Rbac\Repositories\EloquentRoleRepository;
use Caydeesoft\SaasKit\Rbac\Services\RbacService;

final class RbacServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, EloquentPermissionRepository::class);
        $this->app->bind(RbacServiceInterface::class, RbacService::class);
    }
}
