<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Features\Repositories;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Features\Contracts\FeatureRepositoryInterface;
use Caydeesoft\SaasKit\Features\DTOs\FeatureData;
use Caydeesoft\SaasKit\Features\Models\Feature;
use Caydeesoft\SaasKit\Features\Models\PlanFeature;

final class EloquentFeatureRepository implements FeatureRepositoryInterface
{
    public function find(int|string $id): ?Feature
    {
        return Feature::query()->find($id);
    }

    public function findByKey(string $key): ?Feature
    {
        return Feature::query()
            ->where('key', $key)
            ->first();
    }

    public function featuresForPlan(int|string $planId): Collection
    {
        return PlanFeature::query()
            ->with('feature')
            ->where('plan_id', $planId)
            ->get();
    }

    public function create(FeatureData $data): Feature
    {
        return Feature::query()->create($data->toArray());
    }

    public function syncPlanFeature(
        int|string $planId,
        int|string $featureId,
        bool $enabled = true,
        ?int $limit = null,
        array $value = [],
    ): PlanFeature {
        return PlanFeature::query()->updateOrCreate(
            ['plan_id' => $planId, 'feature_id' => $featureId],
            ['enabled' => $enabled, 'limit' => $limit, 'value' => $value],
        );
    }

    public function findPlanFeature(int|string $planId, string $featureKey): ?PlanFeature
    {
        return PlanFeature::query()
            ->where('plan_id', $planId)
            ->whereHas('feature', static fn ($query) => $query->where('key', $featureKey))
            ->first();
    }
}
