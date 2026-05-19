<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Audit\Providers;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Caydeesoft\SaasKit\Audit\Contracts\AuditLogRepositoryInterface;
use Caydeesoft\SaasKit\Audit\Contracts\AuditServiceInterface;
use Caydeesoft\SaasKit\Audit\Listeners\RecordAuditLog;
use Caydeesoft\SaasKit\Audit\Repositories\EloquentAuditLogRepository;
use Caydeesoft\SaasKit\Audit\Services\AuditService;

final class AuditServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuditLogRepositoryInterface::class, EloquentAuditLogRepository::class);
        $this->app->bind(AuditServiceInterface::class, AuditService::class);
    }

    public function boot(Dispatcher $events): void
    {
        if ((bool) config('saas-kit.audit.enabled', true)) {
            $events->listen('*', RecordAuditLog::class);
        }
    }
}
