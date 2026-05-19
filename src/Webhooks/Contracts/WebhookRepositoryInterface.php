<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Webhooks\Contracts;

use Caydeesoft\SaasKit\Webhooks\DTOs\WebhookEventData;
use Caydeesoft\SaasKit\Webhooks\Models\WebhookEvent;

interface WebhookRepositoryInterface
{
    public function find(int|string $id): ?WebhookEvent;

    public function findByProviderEventId(string $provider, string $providerEventId): ?WebhookEvent;

    public function store(WebhookEventData $data): WebhookEvent;

    /**
     * @param array<string, mixed> $attributes
     */
    public function update(WebhookEvent $event, array $attributes): WebhookEvent;
}
