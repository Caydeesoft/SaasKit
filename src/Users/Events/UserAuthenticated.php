<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Events;

use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

final readonly class UserAuthenticated
{
    public function __construct(
        public mixed $user,
        public ?Tenant $tenant = null,
    ) {
    }
}
