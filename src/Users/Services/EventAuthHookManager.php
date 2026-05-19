<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;
use Caydeesoft\SaasKit\Users\Contracts\AuthHookManagerInterface;
use Caydeesoft\SaasKit\Users\Events\UserAuthenticated;

final class EventAuthHookManager implements AuthHookManagerInterface
{
    public function __construct(
        private readonly Dispatcher $events,
    ) {
    }

    public function dispatchAuthenticated(mixed $user, ?Tenant $tenant = null): void
    {
        $this->events->dispatch(new UserAuthenticated($user, $tenant));
    }
}
