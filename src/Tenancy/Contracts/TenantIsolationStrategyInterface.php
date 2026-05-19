<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Contracts;

use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

interface TenantIsolationStrategyInterface
{
    public function initialize(Tenant $tenant): void;

    public function clear(): void;
}
