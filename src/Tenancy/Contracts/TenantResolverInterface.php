<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Contracts;

use Illuminate\Http\Request;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

interface TenantResolverInterface
{
    public function resolve(Request $request): ?Tenant;
}
