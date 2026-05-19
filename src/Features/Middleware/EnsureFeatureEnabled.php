<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Features\Middleware;

use Closure;
use Illuminate\Http\Request;
use Caydeesoft\SaasKit\Billing\Contracts\SubscriptionRepositoryInterface;
use Caydeesoft\SaasKit\Features\Contracts\FeatureGateInterface;
use Caydeesoft\SaasKit\Tenancy\Services\TenantContext;
use Symfony\Component\HttpFoundation\Response;

final class EnsureFeatureEnabled
{
    public function __construct(
        private readonly FeatureGateInterface $features,
        private readonly SubscriptionRepositoryInterface $subscriptions,
        private readonly TenantContext $tenantContext,
    ) {
    }

    public function handle(Request $request, Closure $next, string $featureKey): Response
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

        if ($subscription === null) {
            abort(402, 'An active subscription is required.');
        }

        if (! $this->features->enabledForPlan((string) $subscription->plan_id, $featureKey)) {
            abort(403, 'This feature is not available for the current plan.');
        }

        return $next($request);
    }
}
