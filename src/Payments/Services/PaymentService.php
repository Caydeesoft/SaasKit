<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Payments\Services;

use Caydeesoft\SaasKit\Payments\Contracts\PaymentGatewayRegistryInterface;
use Caydeesoft\SaasKit\Payments\Contracts\PaymentServiceInterface;
use Caydeesoft\SaasKit\Payments\DTOs\CheckoutSessionData;
use Caydeesoft\SaasKit\Payments\DTOs\PaymentResultData;
use Caydeesoft\SaasKit\Webhooks\Contracts\WebhookServiceInterface;
use Caydeesoft\SaasKit\Webhooks\Models\WebhookEvent;

final class PaymentService implements PaymentServiceInterface
{
    public function __construct(
        private readonly PaymentGatewayRegistryInterface $gateways,
        private readonly WebhookServiceInterface $webhooks,
    ) {
    }

    public function createCheckoutSession(
        CheckoutSessionData $data,
        ?string $gateway = null,
    ): PaymentResultData {
        return $this->gateways
            ->gateway($gateway)
            ->createCheckoutSession($data);
    }

    public function handleWebhook(string $gateway, string $payload, array $headers = []): WebhookEvent
    {
        $event = $this->gateways
            ->gateway($gateway)
            ->parseWebhook($payload, $headers);

        return $this->webhooks->receive($event);
    }
}
