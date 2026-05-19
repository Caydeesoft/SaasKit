<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Repositories;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Billing\Contracts\PlanRepositoryInterface;
use Caydeesoft\SaasKit\Billing\DTOs\PlanData;
use Caydeesoft\SaasKit\Billing\Models\Plan;

final class EloquentPlanRepository implements PlanRepositoryInterface
{
    public function find(int|string $id): ?Plan
    {
        return Plan::query()->find($id);
    }

    public function findByKey(string $key): ?Plan
    {
        return Plan::query()
            ->where('key', $key)
            ->first();
    }

    public function active(): Collection
    {
        return Plan::query()
            ->where('active', true)
            ->orderBy('amount')
            ->get();
    }

    public function create(PlanData $data): Plan
    {
        return Plan::query()->create($data->toArray());
    }

    public function update(Plan $plan, array $attributes): Plan
    {
        $plan->fill($attributes);
        $plan->save();

        return $plan->refresh();
    }
}
