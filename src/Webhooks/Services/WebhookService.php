<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Webhooks\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Caydeesoft\SaasKit\Webhooks\Contracts\WebhookEventMapperInterface;
use Caydeesoft\SaasKit\Webhooks\Contracts\WebhookRepositoryInterface;
use Caydeesoft\SaasKit\Webhooks\Contracts\WebhookServiceInterface;
use Caydeesoft\SaasKit\Webhooks\DTOs\WebhookEventData;
use Caydeesoft\SaasKit\Webhooks\Events\PaymentWebhookReceived;
use Caydeesoft\SaasKit\Webhooks\Events\SubscriptionWebhookReceived;
use Caydeesoft\SaasKit\Webhooks\Events\WebhookReceived;
use Caydeesoft\SaasKit\Webhooks\Models\WebhookEvent;

final class WebhookService implements WebhookServiceInterface
{
    public function __construct(
        private readonly WebhookRepositoryInterface $webhooks,
        private readonly WebhookEventMapperInterface $mapper,
        private readonly Dispatcher $events,
    ) {
    }

    public function receive(WebhookEventData $data): WebhookEvent
    {
        $event = $this->webhooks->store($data);
        $mapped = $this->mapper->map($data);

        $this->events->dispatch(new WebhookReceived($data));

        if ($mapped instanceof PaymentWebhookReceived || $mapped instanceof SubscriptionWebhookReceived) {
            $this->events->dispatch($mapped);
        }

        return $event;
    }

    public function markProcessed(WebhookEvent $event): WebhookEvent
    {
        return $this->webhooks->update($event, [
            'status' => 'processed',
            'processed_at' => now(),
        ]);
    }

    public function markFailed(WebhookEvent $event, string $reason): WebhookEvent
    {
        return $this->webhooks->update($event, [
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);
    }
}
