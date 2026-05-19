<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Payments\Contracts;

interface PaymentGatewayRegistryInterface
{
    public function gateway(?string $name = null): PaymentGatewayInterface;
}
