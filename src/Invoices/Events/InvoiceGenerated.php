<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Invoices\Events;

use Caydeesoft\SaasKit\Audit\Events\AuditableEventInterface;
use Caydeesoft\SaasKit\Invoices\Models\Invoice;

final readonly class InvoiceGenerated implements AuditableEventInterface
{
    public function __construct(public Invoice $invoice)
    {
    }

    public function auditPayload(): array
    {
        return [
            'event' => 'invoice.generated',
            'subject_type' => $this->invoice::class,
            'subject_id' => (string) $this->invoice->getKey(),
            'metadata' => ['number' => $this->invoice->number],
        ];
    }
}
