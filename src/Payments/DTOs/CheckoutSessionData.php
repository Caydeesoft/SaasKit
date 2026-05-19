<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Payments\DTOs;

final readonly class CheckoutSessionData
{
    /**
     * @param array<int, array<string, mixed>> $lineItems
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $customerEmail,
        public string $successUrl,
        public string $cancelUrl,
        public array $lineItems,
        public string $mode = 'subscription',
        public ?string $currency = null,
        public array $metadata = [],
    ) {
    }
}
