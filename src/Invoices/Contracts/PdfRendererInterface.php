<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Invoices\Contracts;

use Caydeesoft\SaasKit\Invoices\Models\Invoice;

interface PdfRendererInterface
{
    public function render(Invoice $invoice): string;
}
