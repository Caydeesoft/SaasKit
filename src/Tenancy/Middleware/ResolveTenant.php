<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Middleware;

use Closure;
use Illuminate\Http\Request;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class ResolveTenant
{
    public function __construct(
        private readonly TenantManagerInterface $tenants,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $this->tenants->resolve($request);

        try {
            return $next($request);
        } finally {
            $this->tenants->clear();
        }
    }
}
