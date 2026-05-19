<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Admin\DTOs;

final readonly class DashboardSnapshotData
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public int $tenants,
        public int $users,
        public int $activeSubscriptions,
        public int $openInvoices,
        public int $failedWebhooks,
        public int $auditEvents,
        public array $metadata = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'tenants' => $this->tenants,
            'users' => $this->users,
            'active_subscriptions' => $this->activeSubscriptions,
            'open_invoices' => $this->openInvoices,
            'failed_webhooks' => $this->failedWebhooks,
            'audit_events' => $this->auditEvents,
            'metadata' => $this->metadata,
        ];
    }
}
