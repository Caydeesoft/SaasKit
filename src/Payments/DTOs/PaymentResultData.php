<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Payments\DTOs;

final readonly class PaymentResultData
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public bool $success,
        public string $provider,
        public ?string $providerId,
        public string $status,
        public array $payload = [],
        public ?string $redirectUrl = null,
        public ?string $message = null,
    ) {
    }
}
