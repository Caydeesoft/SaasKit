<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\DTOs;

use DateTimeInterface;

final readonly class SubscriptionData
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $billableType,
        public int|string $billableId,
        public int|string $planId,
        public string $status = 'active',
        public ?DateTimeInterface $trialEndsAt = null,
        public ?DateTimeInterface $startsAt = null,
        public ?DateTimeInterface $endsAt = null,
        public ?string $provider = null,
        public ?string $providerSubscriptionId = null,
        public array $metadata = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'billable_type' => $this->billableType,
            'billable_id' => (string) $this->billableId,
            'plan_id' => $this->planId,
            'status' => $this->status,
            'trial_ends_at' => $this->trialEndsAt,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'provider' => $this->provider,
            'provider_subscription_id' => $this->providerSubscriptionId,
            'metadata' => $this->metadata,
        ];
    }
}
