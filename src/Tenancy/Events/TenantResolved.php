<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Events;

use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

final readonly class TenantResolved
{
    public function __construct(public Tenant $tenant)
    {
    }
}
