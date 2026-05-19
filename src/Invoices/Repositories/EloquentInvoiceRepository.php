<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Invoices\Repositories;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Invoices\Contracts\InvoiceRepositoryInterface;
use Caydeesoft\SaasKit\Invoices\DTOs\InvoiceData;
use Caydeesoft\SaasKit\Invoices\Models\Invoice;

final class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function find(int|string $id): ?Invoice
    {
        return Invoice::query()->find($id);
    }

    public function findByNumber(string $number): ?Invoice
    {
        return Invoice::query()
            ->where('number', $number)
            ->first();
    }

    public function forCustomer(string $customerType, int|string $customerId): Collection
    {
        return Invoice::query()
            ->where('customer_type', $customerType)
            ->where('customer_id', (string) $customerId)
            ->latest('id')
            ->get();
    }

    public function create(InvoiceData $data): Invoice
    {
        return Invoice::query()->create($data->toArray());
    }

    public function update(Invoice $invoice, array $attributes): Invoice
    {
        $invoice->fill($attributes);
        $invoice->save();

        return $invoice->refresh();
    }
}
