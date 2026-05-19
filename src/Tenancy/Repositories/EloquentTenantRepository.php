<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Repositories;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantRepositoryInterface;
use Caydeesoft\SaasKit\Tenancy\DTOs\TenantData;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

final class EloquentTenantRepository implements TenantRepositoryInterface
{
    public function find(int|string $id): ?Tenant
    {
        return Tenant::query()->find($id);
    }

    public function findByKey(string $key): ?Tenant
    {
        return Tenant::query()
            ->where('slug', $key)
            ->orWhere('id', $key)
            ->first();
    }

    public function allActive(): Collection
    {
        return Tenant::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    public function create(TenantData $data): Tenant
    {
        return Tenant::query()->create($data->toArray());
    }

    public function update(Tenant $tenant, array $attributes): Tenant
    {
        $tenant->fill($attributes);
        $tenant->save();

        return $tenant->refresh();
    }

    public function delete(Tenant $tenant): bool
    {
        return (bool) $tenant->delete();
    }
}
