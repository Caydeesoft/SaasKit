<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Contracts;

use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

interface AuthHookManagerInterface
{
    public function dispatchAuthenticated(mixed $user, ?Tenant $tenant = null): void;
}
