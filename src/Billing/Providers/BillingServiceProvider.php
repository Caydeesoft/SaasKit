<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Billing\Contracts\BillingServiceInterface;
use Caydeesoft\SaasKit\Billing\Contracts\CouponRepositoryInterface;
use Caydeesoft\SaasKit\Billing\Contracts\DiscountServiceInterface;
use Caydeesoft\SaasKit\Billing\Contracts\PlanRepositoryInterface;
use Caydeesoft\SaasKit\Billing\Contracts\SubscriptionRepositoryInterface;
use Caydeesoft\SaasKit\Billing\Contracts\UsageRepositoryInterface;
use Caydeesoft\SaasKit\Billing\Repositories\EloquentCouponRepository;
use Caydeesoft\SaasKit\Billing\Repositories\EloquentPlanRepository;
use Caydeesoft\SaasKit\Billing\Repositories\EloquentSubscriptionRepository;
use Caydeesoft\SaasKit\Billing\Repositories\EloquentUsageRepository;
use Caydeesoft\SaasKit\Billing\Services\BillingService;
use Caydeesoft\SaasKit\Billing\Services\DiscountService;

final class BillingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PlanRepositoryInterface::class, EloquentPlanRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, EloquentCouponRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, EloquentSubscriptionRepository::class);
        $this->app->bind(UsageRepositoryInterface::class, EloquentUsageRepository::class);
        $this->app->bind(DiscountServiceInterface::class, DiscountService::class);
        $this->app->bind(BillingServiceInterface::class, BillingService::class);
    }
}
