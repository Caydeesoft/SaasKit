<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\DTOs;

final readonly class PlanData
{
    /**
     * @param array<string, mixed> $features
     * @param array<string, int> $limits
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $name,
        public string $key,
        public string $interval,
        public int $amount,
        public string $currency = 'usd',
        public ?int $trialDays = null,
        public array $features = [],
        public array $limits = [],
        public bool $active = true,
        public array $metadata = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'key' => $this->key,
            'interval' => $this->interval,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'trial_days' => $this->trialDays,
            'features' => $this->features,
            'limits' => $this->limits,
            'active' => $this->active,
            'metadata' => $this->metadata,
        ];
    }
}
