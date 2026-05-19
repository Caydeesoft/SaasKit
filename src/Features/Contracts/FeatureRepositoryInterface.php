<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Features\Contracts;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Features\DTOs\FeatureData;
use Caydeesoft\SaasKit\Features\Models\Feature;
use Caydeesoft\SaasKit\Features\Models\PlanFeature;

interface FeatureRepositoryInterface
{
    public function find(int|string $id): ?Feature;

    public function findByKey(string $key): ?Feature;

    /**
     * @return Collection<int, PlanFeature>
     */
    public function featuresForPlan(int|string $planId): Collection;

    public function create(FeatureData $data): Feature;

    /**
     * @param array<string, mixed> $value
     */
    public function syncPlanFeature(
        int|string $planId,
        int|string $featureId,
        bool $enabled = true,
        ?int $limit = null,
        array $value = [],
    ): PlanFeature;

    public function findPlanFeature(int|string $planId, string $featureKey): ?PlanFeature;
}
