<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Strategies;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantIsolationStrategyInterface;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

final class DatabaseTenantIsolationStrategy implements TenantIsolationStrategyInterface
{
    public function initialize(Tenant $tenant): void
    {
        $metadata = (array) $tenant->metadata;
        $database = $metadata['database'] ?? null;

        if (! is_string($database) || $database === '') {
            return;
        }

        $template = (string) ($metadata['connection_template'] ?? config('database.default'));
        $baseConnection = (array) config("database.connections.$template", []);

        Config::set('database.connections.tenant', array_merge($baseConnection, [
            'database' => $database,
        ]));

        DB::purge('tenant');
        DB::reconnect('tenant');
    }

    public function clear(): void
    {
        DB::purge('tenant');
    }
}
