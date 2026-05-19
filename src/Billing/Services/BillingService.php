<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Services;

use DateTimeInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Caydeesoft\SaasKit\Billing\Contracts\BillingServiceInterface;
use Caydeesoft\SaasKit\Billing\Contracts\CouponRepositoryInterface;
use Caydeesoft\SaasKit\Billing\Contracts\PlanRepositoryInterface;
use Caydeesoft\SaasKit\Billing\Contracts\SubscriptionRepositoryInterface;
use Caydeesoft\SaasKit\Billing\Contracts\UsageRepositoryInterface;
use Caydeesoft\SaasKit\Billing\DTOs\CouponData;
use Caydeesoft\SaasKit\Billing\DTOs\PlanData;
use Caydeesoft\SaasKit\Billing\DTOs\SubscriptionData;
use Caydeesoft\SaasKit\Billing\DTOs\UsageRecordData;
use Caydeesoft\SaasKit\Billing\Events\SubscriptionCancelled;
use Caydeesoft\SaasKit\Billing\Events\SubscriptionStarted;
use Caydeesoft\SaasKit\Billing\Events\TrialStarted;
use Caydeesoft\SaasKit\Billing\Events\UsageLimitExceeded;
use Caydeesoft\SaasKit\Billing\Models\Coupon;
use Caydeesoft\SaasKit\Billing\Models\Plan;
use Caydeesoft\SaasKit\Billing\Models\Subscription;
use Caydeesoft\SaasKit\Billing\Models\UsageRecord;

final class BillingService implements BillingServiceInterface
{
    public function __construct(
        private readonly PlanRepositoryInterface $plans,
        private readonly CouponRepositoryInterface $coupons,
        private readonly SubscriptionRepositoryInterface $subscriptions,
        private readonly UsageRepositoryInterface $usage,
        private readonly Dispatcher $events,
    ) {
    }

    public function createPlan(PlanData $data): Plan
    {
        return $this->plans->create($data);
    }

    public function createCoupon(CouponData $data): Coupon
    {
        return $this->coupons->create($data);
    }

    public function startTrial(
        string $billableType,
        int|string $billableId,
        int|string $planId,
        ?int $trialDays = null,
    ): Subscription {
        $plan = $this->plans->find($planId);
        $days = $trialDays ?? $plan?->trial_days ?? (int) config('saas-kit.billing.trial_days', 14);

        $subscription = $this->subscriptions->create(new SubscriptionData(
            billableType: $billableType,
            billableId: $billableId,
            planId: $planId,
            status: 'trialing',
            trialEndsAt: now()->addDays($days),
            startsAt: now(),
        ));

        $this->events->dispatch(new TrialStarted($subscription));

        return $subscription;
    }

    public function subscribe(
        string $billableType,
        int|string $billableId,
        int|string $planId,
        ?DateTimeInterface $startsAt = null,
    ): Subscription {
        $subscription = $this->subscriptions->create(new SubscriptionData(
            billableType: $billableType,
            billableId: $billableId,
            planId: $planId,
            status: 'active',
            startsAt: $startsAt ?? now(),
        ));

        $this->events->dispatch(new SubscriptionStarted($subscription));

        return $subscription;
    }

    public function cancelSubscription(Subscription $subscription, bool $immediately = false): Subscription
    {
        $cancelled = $this->subscriptions->cancel($subscription, $immediately);
        $this->events->dispatch(new SubscriptionCancelled($cancelled));

        return $cancelled;
    }

    public function recordUsage(UsageRecordData $data): UsageRecord
    {
        $record = $this->usage->record($data);

        if (! $this->hasUsageRemaining($data->subscriptionId, $data->featureKey, 0)) {
            $this->events->dispatch(new UsageLimitExceeded($record));
        }

        return $record;
    }

    public function hasUsageRemaining(int|string $subscriptionId, string $featureKey, int $quantity = 1): bool
    {
        $subscription = $this->subscriptions->find($subscriptionId);

        if ($subscription === null) {
            return false;
        }

        $limits = (array) ($subscription->plan?->limits ?? []);
        $limit = $limits[$featureKey] ?? null;

        if (! is_int($limit) && ! is_numeric($limit)) {
            return true;
        }

        return ($this->usage->sumForSubscriptionFeature($subscriptionId, $featureKey) + $quantity) <= (int) $limit;
    }
}
