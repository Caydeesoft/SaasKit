<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Webhooks\DTOs;

use DateTimeInterface;

final readonly class WebhookEventData
{
    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $headers
     */
    public function __construct(
        public string $provider,
        public string $eventType,
        public string $providerEventId,
        public array $payload,
        public array $headers = [],
        public ?DateTimeInterface $receivedAt = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'provider' => $this->provider,
            'event_type' => $this->eventType,
            'provider_event_id' => $this->providerEventId,
            'payload' => $this->payload,
            'headers' => $this->headers,
            'status' => 'received',
            'received_at' => $this->receivedAt ?? now(),
        ];
    }
}
