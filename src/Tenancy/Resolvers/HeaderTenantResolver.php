<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Resolvers;

use Illuminate\Http\Request;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantRepositoryInterface;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantResolverInterface;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

final class HeaderTenantResolver implements TenantResolverInterface
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenants,
    ) {
    }

    public function resolve(Request $request): ?Tenant
    {
        $header = (string) config('saas-kit.tenant.header', 'X-Tenant-ID');
        $tenantKey = $request->header($header) ?: $request->route(
            (string) config('saas-kit.tenant.route_parameter', 'tenant')
        );

        if (! is_string($tenantKey) || $tenantKey === '') {
            return null;
        }

        return $this->tenants->findByKey($tenantKey);
    }
}
