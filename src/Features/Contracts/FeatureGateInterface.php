<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Features\Contracts;

use Caydeesoft\SaasKit\Features\DTOs\FeatureData;
use Caydeesoft\SaasKit\Features\Models\Feature;
use Caydeesoft\SaasKit\Features\Models\PlanFeature;

interface FeatureGateInterface
{
    public function createFeature(FeatureData $data): Feature;

    /**
     * @param array<string, mixed> $value
     */
    public function enableForPlan(
        int|string $planId,
        string $featureKey,
        ?int $limit = null,
        array $value = [],
    ): PlanFeature;

    public function enabledForPlan(int|string $planId, string $featureKey): bool;

    public function limitForPlan(int|string $planId, string $featureKey): ?int;

    public function assertEnabledForPlan(int|string $planId, string $featureKey): void;
}
