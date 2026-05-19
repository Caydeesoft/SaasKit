<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Admin\Services;

use Caydeesoft\SaasKit\Admin\Contracts\AdminDashboardServiceInterface;
use Caydeesoft\SaasKit\Admin\DTOs\DashboardSnapshotData;
use Caydeesoft\SaasKit\Audit\Models\AuditLog;
use Caydeesoft\SaasKit\Billing\Models\Subscription;
use Caydeesoft\SaasKit\Invoices\Models\Invoice;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;
use Caydeesoft\SaasKit\Users\Models\SaasUser;
use Caydeesoft\SaasKit\Webhooks\Models\WebhookEvent;

final class AdminDashboardService implements AdminDashboardServiceInterface
{
    public function snapshot(): DashboardSnapshotData
    {
        return new DashboardSnapshotData(
            tenants: Tenant::query()->count(),
            users: SaasUser::query()->count(),
            activeSubscriptions: Subscription::query()->whereIn('status', ['active', 'trialing'])->count(),
            openInvoices: Invoice::query()->where('status', 'open')->count(),
            failedWebhooks: WebhookEvent::query()->where('status', 'failed')->count(),
            auditEvents: AuditLog::query()->count(),
        );
    }
}
