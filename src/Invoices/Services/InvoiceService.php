<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Invoices\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Caydeesoft\SaasKit\Billing\Models\Subscription;
use Caydeesoft\SaasKit\Invoices\Contracts\InvoiceRepositoryInterface;
use Caydeesoft\SaasKit\Invoices\Contracts\InvoiceServiceInterface;
use Caydeesoft\SaasKit\Invoices\Contracts\PdfRendererInterface;
use Caydeesoft\SaasKit\Invoices\DTOs\InvoiceData;
use Caydeesoft\SaasKit\Invoices\DTOs\InvoiceLineData;
use Caydeesoft\SaasKit\Invoices\Events\InvoiceGenerated;
use Caydeesoft\SaasKit\Invoices\Events\InvoicePaid;
use Caydeesoft\SaasKit\Invoices\Models\Invoice;

final class InvoiceService implements InvoiceServiceInterface
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoices,
        private readonly PdfRendererInterface $renderer,
        private readonly Dispatcher $events,
    ) {
    }

    public function generateForSubscription(
        Subscription $subscription,
        array $lines,
        ?string $customerType = null,
        int|string|null $customerId = null,
    ): Invoice {
        $subtotal = array_sum(array_map(
            static fn (InvoiceLineData $line): int => $line->total(),
            $lines,
        ));

        $invoice = $this->invoices->create(new InvoiceData(
            number: $this->nextInvoiceNumber(),
            customerType: $customerType ?? $subscription->billable_type,
            customerId: $customerId ?? $subscription->billable_id,
            subscriptionId: (int) $subscription->getKey(),
            currency: (string) ($subscription->plan?->currency ?? config('saas-kit.billing.currency', 'usd')),
            subtotal: $subtotal,
            discountTotal: 0,
            taxTotal: 0,
            total: $subtotal,
            lines: $lines,
            dueAt: now()->addDays(14),
        ));

        $this->events->dispatch(new InvoiceGenerated($invoice));

        return $invoice;
    }

    public function markPaid(Invoice $invoice, ?string $providerPaymentId = null): Invoice
    {
        $paid = $this->invoices->update($invoice, [
            'status' => 'paid',
            'paid_at' => now(),
            'provider_payment_id' => $providerPaymentId,
        ]);

        $this->events->dispatch(new InvoicePaid($paid));

        return $paid;
    }

    public function exportPdf(Invoice $invoice): string
    {
        return $this->renderer->render($invoice);
    }

    private function nextInvoiceNumber(): string
    {
        $prefix = (string) config('saas-kit.invoices.number_prefix', 'INV');

        return sprintf('%s-%s-%s', $prefix, now()->format('Ymd'), strtoupper(bin2hex(random_bytes(3))));
    }
}
