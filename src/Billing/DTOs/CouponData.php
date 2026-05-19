<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\DTOs;

use DateTimeInterface;

final readonly class CouponData
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $code,
        public string $type,
        public int $value,
        public ?DateTimeInterface $validFrom = null,
        public ?DateTimeInterface $validUntil = null,
        public ?int $maxRedemptions = null,
        public array $metadata = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'value' => $this->value,
            'valid_from' => $this->validFrom,
            'valid_until' => $this->validUntil,
            'max_redemptions' => $this->maxRedemptions,
            'metadata' => $this->metadata,
        ];
    }
}
