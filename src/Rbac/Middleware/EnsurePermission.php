<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\Middleware;

use Closure;
use Illuminate\Http\Request;
use Caydeesoft\SaasKit\Rbac\Contracts\RbacServiceInterface;
use Symfony\Component\HttpFoundation\Response;

final class EnsurePermission
{
    public function __construct(
        private readonly RbacServiceInterface $rbac,
    ) {
    }

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if ($user === null || ! method_exists($user, 'getKey')) {
            abort(403);
        }

        if (! $this->rbac->userHasPermission((string) $user->getKey(), $permission, $user::class)) {
            abort(403);
        }

        return $next($request);
    }
}
