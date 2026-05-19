<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\DTOs;

use DateTimeInterface;

final readonly class UsageRecordData
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public int|string $subscriptionId,
        public string $featureKey,
        public int $quantity = 1,
        public ?DateTimeInterface $occurredAt = null,
        public array $metadata = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'subscription_id' => $this->subscriptionId,
            'feature_key' => $this->featureKey,
            'quantity' => $this->quantity,
            'occurred_at' => $this->occurredAt ?? now(),
            'metadata' => $this->metadata,
        ];
    }
}
