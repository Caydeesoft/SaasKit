<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Payments\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Payments\Contracts\PaymentGatewayRegistryInterface;
use Caydeesoft\SaasKit\Payments\Contracts\PaymentServiceInterface;
use Caydeesoft\SaasKit\Payments\Services\PaymentGatewayRegistry;
use Caydeesoft\SaasKit\Payments\Services\PaymentService;

final class PaymentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayRegistryInterface::class, PaymentGatewayRegistry::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
    }
}
