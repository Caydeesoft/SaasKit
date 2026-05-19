<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Features\Services;

use Caydeesoft\SaasKit\Features\Contracts\FeatureGateInterface;
use Caydeesoft\SaasKit\Features\Contracts\FeatureRepositoryInterface;
use Caydeesoft\SaasKit\Features\DTOs\FeatureData;
use Caydeesoft\SaasKit\Features\Exceptions\FeatureNotAvailableException;
use Caydeesoft\SaasKit\Features\Models\Feature;
use Caydeesoft\SaasKit\Features\Models\PlanFeature;

final class FeatureGate implements FeatureGateInterface
{
    public function __construct(
        private readonly FeatureRepositoryInterface $features,
    ) {
    }

    public function createFeature(FeatureData $data): Feature
    {
        return $this->features->create($data);
    }

    public function enableForPlan(
        int|string $planId,
        string $featureKey,
        ?int $limit = null,
        array $value = [],
    ): PlanFeature {
        $feature = $this->features->findByKey($featureKey);

        if ($feature === null) {
            throw new FeatureNotAvailableException("Feature [$featureKey] does not exist.");
        }

        return $this->features->syncPlanFeature($planId, (int) $feature->getKey(), true, $limit, $value);
    }

    public function enabledForPlan(int|string $planId, string $featureKey): bool
    {
        $planFeature = $this->features->findPlanFeature($planId, $featureKey);

        return $planFeature !== null && (bool) $planFeature->enabled;
    }

    public function limitForPlan(int|string $planId, string $featureKey): ?int
    {
        $planFeature = $this->features->findPlanFeature($planId, $featureKey);

        return $planFeature?->limit;
    }

    public function assertEnabledForPlan(int|string $planId, string $featureKey): void
    {
        if (! $this->enabledForPlan($planId, $featureKey)) {
            throw new FeatureNotAvailableException("Feature [$featureKey] is not enabled for this plan.");
        }
    }
}
