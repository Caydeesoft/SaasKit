<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tests\Unit;

use Caydeesoft\SaasKit\Admin\Contracts\AdminDashboardServiceInterface;
use Caydeesoft\SaasKit\Api\Contracts\ApiResourceSerializerInterface;
use Caydeesoft\SaasKit\Audit\Contracts\AuditServiceInterface;
use Caydeesoft\SaasKit\Billing\Contracts\BillingServiceInterface;
use Caydeesoft\SaasKit\Features\Contracts\FeatureGateInterface;
use Caydeesoft\SaasKit\Invoices\Contracts\InvoiceServiceInterface;
use Caydeesoft\SaasKit\Payments\Contracts\PaymentServiceInterface;
use Caydeesoft\SaasKit\Rbac\Contracts\RbacServiceInterface;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantManagerInterface;
use Caydeesoft\SaasKit\Tests\TestCase;
use Caydeesoft\SaasKit\Users\Contracts\UserServiceInterface;
use Caydeesoft\SaasKit\Webhooks\Contracts\WebhookServiceInterface;

final class ServiceProviderTest extends TestCase
{
    public function test_it_registers_core_service_bindings(): void
    {
        self::assertInstanceOf(TenantManagerInterface::class, $this->app->make(TenantManagerInterface::class));
        self::assertInstanceOf(UserServiceInterface::class, $this->app->make(UserServiceInterface::class));
        self::assertInstanceOf(RbacServiceInterface::class, $this->app->make(RbacServiceInterface::class));
        self::assertInstanceOf(BillingServiceInterface::class, $this->app->make(BillingServiceInterface::class));
        self::assertInstanceOf(PaymentServiceInterface::class, $this->app->make(PaymentServiceInterface::class));
        self::assertInstanceOf(InvoiceServiceInterface::class, $this->app->make(InvoiceServiceInterface::class));
        self::assertInstanceOf(WebhookServiceInterface::class, $this->app->make(WebhookServiceInterface::class));
        self::assertInstanceOf(FeatureGateInterface::class, $this->app->make(FeatureGateInterface::class));
        self::assertInstanceOf(AdminDashboardServiceInterface::class, $this->app->make(AdminDashboardServiceInterface::class));
        self::assertInstanceOf(AuditServiceInterface::class, $this->app->make(AuditServiceInterface::class));
        self::assertInstanceOf(ApiResourceSerializerInterface::class, $this->app->make(ApiResourceSerializerInterface::class));
    }

    public function test_it_registers_the_health_route(): void
    {
        $this->get('/api/saas-kit/health')
            ->assertOk()
            ->assertJson(['package' => 'caydeesoft/saas-kit', 'status' => 'ok']);
    }
}
