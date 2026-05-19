<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Contracts;

use Illuminate\Http\Request;
use Caydeesoft\SaasKit\Tenancy\DTOs\TenantData;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

interface TenantManagerInterface
{
    public function resolve(Request $request): ?Tenant;

    public function set(Tenant $tenant): void;

    public function current(): ?Tenant;

    public function create(TenantData $data): Tenant;

    public function clear(): void;
}
