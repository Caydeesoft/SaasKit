<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Payments\Contracts;

use Caydeesoft\SaasKit\Payments\DTOs\CheckoutSessionData;
use Caydeesoft\SaasKit\Payments\DTOs\PaymentResultData;
use Caydeesoft\SaasKit\Webhooks\Models\WebhookEvent;

interface PaymentServiceInterface
{
    public function createCheckoutSession(
        CheckoutSessionData $data,
        ?string $gateway = null,
    ): PaymentResultData;

    /**
     * @param array<string, mixed> $headers
     */
    public function handleWebhook(string $gateway, string $payload, array $headers = []): WebhookEvent;
}
