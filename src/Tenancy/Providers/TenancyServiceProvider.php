<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantIsolationStrategyInterface;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantManagerInterface;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantRepositoryInterface;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantResolverInterface;
use Caydeesoft\SaasKit\Tenancy\Repositories\EloquentTenantRepository;
use Caydeesoft\SaasKit\Tenancy\Services\TenantContext;
use Caydeesoft\SaasKit\Tenancy\Services\TenantManager;

final class TenancyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantContext::class);
        $this->app->bind(TenantRepositoryInterface::class, EloquentTenantRepository::class);
        $this->app->bind(TenantManagerInterface::class, TenantManager::class);

        $this->app->bind(TenantResolverInterface::class, function ($app): TenantResolverInterface {
            return $app->make((string) config('saas-kit.tenant.resolver'));
        });

        $this->app->bind(TenantIsolationStrategyInterface::class, function ($app): TenantIsolationStrategyInterface {
            return $app->make((string) config('saas-kit.tenant.isolation_strategy'));
        });
    }
}
