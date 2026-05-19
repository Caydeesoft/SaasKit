<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Contracts;

use DateTimeInterface;
use Caydeesoft\SaasKit\Billing\DTOs\CouponData;
use Caydeesoft\SaasKit\Billing\DTOs\PlanData;
use Caydeesoft\SaasKit\Billing\DTOs\UsageRecordData;
use Caydeesoft\SaasKit\Billing\Models\Coupon;
use Caydeesoft\SaasKit\Billing\Models\Plan;
use Caydeesoft\SaasKit\Billing\Models\Subscription;
use Caydeesoft\SaasKit\Billing\Models\UsageRecord;

interface BillingServiceInterface
{
    public function createPlan(PlanData $data): Plan;

    public function createCoupon(CouponData $data): Coupon;

    public function startTrial(
        string $billableType,
        int|string $billableId,
        int|string $planId,
        ?int $trialDays = null,
    ): Subscription;

    public function subscribe(
        string $billableType,
        int|string $billableId,
        int|string $planId,
        ?DateTimeInterface $startsAt = null,
    ): Subscription;

    public function cancelSubscription(Subscription $subscription, bool $immediately = false): Subscription;

    public function recordUsage(UsageRecordData $data): UsageRecord;

    public function hasUsageRemaining(int|string $subscriptionId, string $featureKey, int $quantity = 1): bool;
}
