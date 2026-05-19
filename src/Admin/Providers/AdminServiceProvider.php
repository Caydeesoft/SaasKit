<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Admin\Contracts\AdminDashboardServiceInterface;
use Caydeesoft\SaasKit\Admin\Services\AdminDashboardService;

final class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AdminDashboardServiceInterface::class, AdminDashboardService::class);
    }
}
