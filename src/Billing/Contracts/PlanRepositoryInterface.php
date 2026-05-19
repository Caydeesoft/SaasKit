<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Contracts;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Billing\DTOs\PlanData;
use Caydeesoft\SaasKit\Billing\Models\Plan;

interface PlanRepositoryInterface
{
    public function find(int|string $id): ?Plan;

    public function findByKey(string $key): ?Plan;

    /**
     * @return Collection<int, Plan>
     */
    public function active(): Collection;

    public function create(PlanData $data): Plan;

    /**
     * @param array<string, mixed> $attributes
     */
    public function update(Plan $plan, array $attributes): Plan;
}
