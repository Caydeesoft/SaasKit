<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Payments\Contracts;

use Caydeesoft\SaasKit\Payments\DTOs\CheckoutSessionData;
use Caydeesoft\SaasKit\Payments\DTOs\PaymentResultData;
use Caydeesoft\SaasKit\Webhooks\DTOs\WebhookEventData;

interface PaymentGatewayInterface
{
    public function name(): string;

    public function createCheckoutSession(CheckoutSessionData $data): PaymentResultData;

    public function cancelSubscription(string $providerSubscriptionId): PaymentResultData;

    /**
     * @param array<string, mixed> $headers
     */
    public function parseWebhook(string $payload, array $headers = []): WebhookEventData;
}
