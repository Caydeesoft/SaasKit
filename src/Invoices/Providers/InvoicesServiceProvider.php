<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Invoices\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Invoices\Contracts\InvoiceRepositoryInterface;
use Caydeesoft\SaasKit\Invoices\Contracts\InvoiceServiceInterface;
use Caydeesoft\SaasKit\Invoices\Contracts\PdfRendererInterface;
use Caydeesoft\SaasKit\Invoices\Repositories\EloquentInvoiceRepository;
use Caydeesoft\SaasKit\Invoices\Services\InvoiceService;

final class InvoicesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(InvoiceRepositoryInterface::class, EloquentInvoiceRepository::class);
        $this->app->bind(InvoiceServiceInterface::class, InvoiceService::class);

        $this->app->bind(PdfRendererInterface::class, function ($app): PdfRendererInterface {
            return $app->make((string) config('saas-kit.invoices.renderer'));
        });
    }
}
