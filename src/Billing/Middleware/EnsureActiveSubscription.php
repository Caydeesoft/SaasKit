<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Middleware;

use Closure;
use Illuminate\Http\Request;
use Caydeesoft\SaasKit\Billing\Contracts\SubscriptionRepositoryInterface;
use Caydeesoft\SaasKit\Tenancy\Services\TenantContext;
use Symfony\Component\HttpFoundation\Response;

final class EnsureActiveSubscription
{
    public function __construct(
        private readonly SubscriptionRepositoryInterface $subscriptions,
        private readonly TenantContext $tenantContext,
    ) {
    }

    public function handle(Request $request, Closure $next, ?string $planKey = null): Response
    {
        $subscription = null;
        $tenant = $this->tenantContext->get();

        if ($tenant !== null) {
            $subscription = $this->subscriptions->activeForBillable($tenant::class, (string) $tenant->getKey());
        }

        $user = $request->user();

        if ($subscription === null && $user !== null && method_exists($user, 'getKey')) {
            $subscription = $this->subscriptions->activeForBillable($user::class, (string) $user->getKey());
        }

        if ($subscription === null || ! $subscription->isActive()) {
            abort(402, 'An active subscription is required.');
        }

        if ($planKey !== null && $subscription->plan?->key !== $planKey) {
            abort(403, 'The current plan does not allow this action.');
        }

        return $next($request);
    }
}
