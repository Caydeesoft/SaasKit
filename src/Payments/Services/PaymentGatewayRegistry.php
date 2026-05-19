<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Payments\Services;

use Illuminate\Contracts\Container\Container;
use Caydeesoft\SaasKit\Payments\Contracts\PaymentGatewayInterface;
use Caydeesoft\SaasKit\Payments\Contracts\PaymentGatewayRegistryInterface;
use Caydeesoft\SaasKit\Payments\Exceptions\PaymentGatewayException;

final class PaymentGatewayRegistry implements PaymentGatewayRegistryInterface
{
    public function __construct(
        private readonly Container $container,
    ) {
    }

    public function gateway(?string $name = null): PaymentGatewayInterface
    {
        $gatewayName = $name ?? (string) config('saas-kit.payments.default', 'stripe');
        $gateways = (array) config('saas-kit.payments.gateways', []);
        $gatewayClass = $gateways[$gatewayName] ?? null;

        if (! is_string($gatewayClass)) {
            throw new PaymentGatewayException("Payment gateway [$gatewayName] is not configured.");
        }

        $gateway = $this->container->make($gatewayClass);

        if (! $gateway instanceof PaymentGatewayInterface) {
            throw new PaymentGatewayException("Payment gateway [$gatewayName] must implement PaymentGatewayInterface.");
        }

        return $gateway;
    }
}
