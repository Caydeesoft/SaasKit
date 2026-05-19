<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Invoices\Contracts;

use Caydeesoft\SaasKit\Billing\Models\Subscription;
use Caydeesoft\SaasKit\Invoices\DTOs\InvoiceLineData;
use Caydeesoft\SaasKit\Invoices\Models\Invoice;

interface InvoiceServiceInterface
{
    /**
     * @param array<int, InvoiceLineData> $lines
     */
    public function generateForSubscription(
        Subscription $subscription,
        array $lines,
        ?string $customerType = null,
        int|string|null $customerId = null,
    ): Invoice;

    public function markPaid(Invoice $invoice, ?string $providerPaymentId = null): Invoice;

    public function exportPdf(Invoice $invoice): string;
}
