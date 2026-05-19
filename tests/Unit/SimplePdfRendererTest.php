<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Caydeesoft\SaasKit\Invoices\Models\Invoice;
use Caydeesoft\SaasKit\Invoices\Services\SimplePdfRenderer;

final class SimplePdfRendererTest extends TestCase
{
    public function test_it_renders_a_pdf_string(): void
    {
        Invoice::unsetEventDispatcher();

        $invoice = new Invoice([
            'number' => 'INV-TEST',
            'status' => 'open',
            'currency' => 'usd',
            'subtotal' => 1000,
            'discount_total' => 0,
            'tax_total' => 0,
            'total' => 1000,
            'lines' => [
                [
                    'description' => 'Growth plan',
                    'quantity' => 1,
                    'unit_amount' => 1000,
                    'total' => 1000,
                ],
            ],
        ]);

        $pdf = (new SimplePdfRenderer())->render($invoice);

        self::assertStringStartsWith('%PDF-1.4', $pdf);
        self::assertStringContainsString('INV-TEST', $pdf);
    }
}
