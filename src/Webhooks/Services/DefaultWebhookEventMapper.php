<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Webhooks\Services;

use Caydeesoft\SaasKit\Webhooks\Contracts\WebhookEventMapperInterface;
use Caydeesoft\SaasKit\Webhooks\DTOs\WebhookEventData;
use Caydeesoft\SaasKit\Webhooks\Events\PaymentWebhookReceived;
use Caydeesoft\SaasKit\Webhooks\Events\SubscriptionWebhookReceived;
use Caydeesoft\SaasKit\Webhooks\Events\WebhookReceived;

final class DefaultWebhookEventMapper implements WebhookEventMapperInterface
{
    public function map(WebhookEventData $data): object
    {
        if (str_contains($data->eventType, 'payment') || str_contains($data->eventType, 'invoice')) {
            return new PaymentWebhookReceived($data);
        }

        if (str_contains($data->eventType, 'subscription')) {
            return new SubscriptionWebhookReceived($data);
        }

        return new WebhookReceived($data);
    }
}
