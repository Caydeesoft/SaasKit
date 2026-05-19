<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Strategies;

use Caydeesoft\SaasKit\Tenancy\Contracts\TenantIsolationStrategyInterface;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

final class NoopTenantIsolationStrategy implements TenantIsolationStrategyInterface
{
    public function initialize(Tenant $tenant): void
    {
    }

    public function clear(): void
    {
    }
}
