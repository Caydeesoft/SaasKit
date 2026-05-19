<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantIsolationStrategyInterface;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantManagerInterface;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantRepositoryInterface;
use Caydeesoft\SaasKit\Tenancy\Contracts\TenantResolverInterface;
use Caydeesoft\SaasKit\Tenancy\DTOs\TenantData;
use Caydeesoft\SaasKit\Tenancy\Events\TenantCreated;
use Caydeesoft\SaasKit\Tenancy\Events\TenantResolved;
use Caydeesoft\SaasKit\Tenancy\Models\Tenant;

final class TenantManager implements TenantManagerInterface
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenants,
        private readonly TenantResolverInterface $resolver,
        private readonly TenantIsolationStrategyInterface $isolation,
        private readonly TenantContext $context,
        private readonly Dispatcher $events,
    ) {
    }

    public function resolve(Request $request): ?Tenant
    {
        $tenant = $this->resolver->resolve($request);

        if ($tenant !== null) {
            $this->set($tenant);
            $this->events->dispatch(new TenantResolved($tenant));
        }

        return $tenant;
    }

    public function set(Tenant $tenant): void
    {
        $this->context->set($tenant);
        $this->isolation->initialize($tenant);
    }

    public function current(): ?Tenant
    {
        return $this->context->get();
    }

    public function create(TenantData $data): Tenant
    {
        $tenant = $this->tenants->create($data);
        $this->events->dispatch(new TenantCreated($tenant));

        return $tenant;
    }

    public function clear(): void
    {
        $this->isolation->clear();
        $this->context->clear();
    }
}
