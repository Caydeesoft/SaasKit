<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Invoices\Services;

use Caydeesoft\SaasKit\Invoices\Contracts\PdfRendererInterface;
use Caydeesoft\SaasKit\Invoices\Models\Invoice;

final class SimplePdfRenderer implements PdfRendererInterface
{
    public function render(Invoice $invoice): string
    {
        $lines = [
            'Invoice ' . $invoice->number,
            'Status: ' . $invoice->status,
            'Currency: ' . strtoupper((string) $invoice->currency),
            'Subtotal: ' . $invoice->subtotal,
            'Discount: ' . $invoice->discount_total,
            'Tax: ' . $invoice->tax_total,
            'Total: ' . $invoice->total,
            '',
        ];

        foreach ((array) $invoice->lines as $line) {
            $lines[] = sprintf(
                '%s x%s @ %s = %s',
                (string) ($line['description'] ?? ''),
                (string) ($line['quantity'] ?? 1),
                (string) ($line['unit_amount'] ?? 0),
                (string) ($line['total'] ?? 0),
            );
        }

        return $this->minimalPdf($lines);
    }

    /**
     * @param array<int, string> $lines
     */
    private function minimalPdf(array $lines): string
    {
        $content = "BT\n/F1 12 Tf\n72 760 Td\n";

        foreach ($lines as $index => $line) {
            if ($index > 0) {
                $content .= "0 -18 Td\n";
            }

            $content .= '(' . $this->escapePdfText($line) . ") Tj\n";
        }

        $content .= "ET\n";

        $objects = [
            '1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj',
            '2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj',
            '3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj',
            '4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj',
            '5 0 obj << /Length ' . strlen($content) . " >> stream\n" . $content . 'endstream endobj',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object . "\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        $pdf .= "trailer << /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xref . "\n%%EOF";

        return $pdf;
    }

    private function escapePdfText(string $text): string
    {
        return str_replace(
            ['\\', '(', ')', "\r"],
            ['\\\\', '\\(', '\\)', ''],
            $text,
        );
    }
}
