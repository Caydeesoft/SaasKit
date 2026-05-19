<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Contracts;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Tenancy\DTOs\TenantData;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

interface TenantRepositoryInterface
{
    public function find(int|string $id): ?Tenant;

    public function findByKey(string $key): ?Tenant;

    /**
     * @return Collection<int, Tenant>
     */
    public function allActive(): Collection;

    public function create(TenantData $data): Tenant;

    /**
     * @param array<string, mixed> $attributes
     */
    public function update(Tenant $tenant, array $attributes): Tenant;

    public function delete(Tenant $tenant): bool;
}
