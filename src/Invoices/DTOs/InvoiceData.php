<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Invoices\DTOs;

use DateTimeInterface;

final readonly class InvoiceData
{
    /**
     * @param array<int, InvoiceLineData> $lines
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $number,
        public string $customerType,
        public int|string $customerId,
        public ?int $subscriptionId,
        public string $currency,
        public int $subtotal,
        public int $discountTotal,
        public int $taxTotal,
        public int $total,
        public array $lines,
        public string $status = 'open',
        public ?DateTimeInterface $dueAt = null,
        public array $metadata = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'number' => $this->number,
            'customer_type' => $this->customerType,
            'customer_id' => (string) $this->customerId,
            'subscription_id' => $this->subscriptionId,
            'currency' => $this->currency,
            'subtotal' => $this->subtotal,
            'discount_total' => $this->discountTotal,
            'tax_total' => $this->taxTotal,
            'total' => $this->total,
            'lines' => array_map(
                static fn (InvoiceLineData $line): array => $line->toArray(),
                $this->lines,
            ),
            'status' => $this->status,
            'due_at' => $this->dueAt,
            'metadata' => $this->metadata,
        ];
    }
}
