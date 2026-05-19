<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Invoices\Contracts;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Invoices\DTOs\InvoiceData;
use Caydeesoft\SaasKit\Invoices\Models\Invoice;

interface InvoiceRepositoryInterface
{
    public function find(int|string $id): ?Invoice;

    public function findByNumber(string $number): ?Invoice;

    /**
     * @return Collection<int, Invoice>
     */
    public function forCustomer(string $customerType, int|string $customerId): Collection;

    public function create(InvoiceData $data): Invoice;

    /**
     * @param array<string, mixed> $attributes
     */
    public function update(Invoice $invoice, array $attributes): Invoice;
}
