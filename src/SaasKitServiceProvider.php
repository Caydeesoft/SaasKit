<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Admin\Providers\AdminServiceProvider;
use Caydeesoft\SaasKit\Api\Providers\ApiServiceProvider;
use Caydeesoft\SaasKit\Audit\Providers\AuditServiceProvider;
use Caydeesoft\SaasKit\Billing\Middleware\EnsureActiveSubscription;
use Caydeesoft\SaasKit\Billing\Providers\BillingServiceProvider;
use Caydeesoft\SaasKit\Features\Middleware\EnsureFeatureEnabled;
use Caydeesoft\SaasKit\Features\Providers\FeaturesServiceProvider;
use Caydeesoft\SaasKit\Invoices\Providers\InvoicesServiceProvider;
use Caydeesoft\SaasKit\Mcp\Providers\McpServiceProvider;
use Caydeesoft\SaasKit\Payments\Providers\PaymentsServiceProvider;
use Caydeesoft\SaasKit\Rbac\Middleware\EnsurePermission;
use Caydeesoft\SaasKit\Rbac\Providers\RbacServiceProvider;
use Caydeesoft\SaasKit\Seo\Providers\SeoServiceProvider;
use Caydeesoft\SaasKit\Tenancy\Middleware\ResolveTenant;
use Caydeesoft\SaasKit\Tenancy\Providers\TenancyServiceProvider;
use Caydeesoft\SaasKit\Users\Providers\UsersServiceProvider;
use Caydeesoft\SaasKit\Webhooks\Providers\WebhooksServiceProvider;

final class SaasKitServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string<ServiceProvider>>
     */
    private array $moduleProviders = [
        TenancyServiceProvider::class,
        UsersServiceProvider::class,
        RbacServiceProvider::class,
        BillingServiceProvider::class,
        PaymentsServiceProvider::class,
        InvoicesServiceProvider::class,
        WebhooksServiceProvider::class,
        FeaturesServiceProvider::class,
        AdminServiceProvider::class,
        AuditServiceProvider::class,
        ApiServiceProvider::class,
        SeoServiceProvider::class,
        McpServiceProvider::class,
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/saas-kit.php', 'saas-kit');

        foreach ($this->moduleProviders as $provider) {
            $this->app->register($provider);
        }
    }

    public function boot(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('saas.tenant', ResolveTenant::class);
        $router->aliasMiddleware('saas.subscription', EnsureActiveSubscription::class);
        $router->aliasMiddleware('saas.feature', EnsureFeatureEnabled::class);
        $router->aliasMiddleware('saas.permission', EnsurePermission::class);

        $this->publishes([
            __DIR__ . '/../config/saas-kit.php' => config_path('saas-kit.php'),
        ], 'saas-kit-config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'saas-kit-migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ((bool) config('saas-kit.api.enabled', true)) {
            Route::middleware((array) config('saas-kit.api.middleware', ['api']))
                ->prefix((string) config('saas-kit.api.prefix', 'api/saas-kit'))
                ->group(__DIR__ . '/../routes/api.php');
        }
    }
}
