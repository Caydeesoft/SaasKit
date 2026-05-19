<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Webhooks\Repositories;

use Caydeesoft\SaasKit\Webhooks\Contracts\WebhookRepositoryInterface;
use Caydeesoft\SaasKit\Webhooks\DTOs\WebhookEventData;
use Caydeesoft\SaasKit\Webhooks\Models\WebhookEvent;

final class EloquentWebhookRepository implements WebhookRepositoryInterface
{
    public function find(int|string $id): ?WebhookEvent
    {
        return WebhookEvent::query()->find($id);
    }

    public function findByProviderEventId(string $provider, string $providerEventId): ?WebhookEvent
    {
        return WebhookEvent::query()
            ->where('provider', $provider)
            ->where('provider_event_id', $providerEventId)
            ->first();
    }

    public function store(WebhookEventData $data): WebhookEvent
    {
        $existing = $this->findByProviderEventId($data->provider, $data->providerEventId);

        if ($existing !== null) {
            return $existing;
        }

        return WebhookEvent::query()->create($data->toArray());
    }

    public function update(WebhookEvent $event, array $attributes): WebhookEvent
    {
        $event->fill($attributes);
        $event->save();

        return $event->refresh();
    }
}
