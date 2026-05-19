<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Invoices\DTOs;

final readonly class InvoiceLineData
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $description,
        public int $quantity,
        public int $unitAmount,
        public array $metadata = [],
    ) {
    }

    public function total(): int
    {
        return $this->quantity * $this->unitAmount;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit_amount' => $this->unitAmount,
            'total' => $this->total(),
            'metadata' => $this->metadata,
        ];
    }
}
