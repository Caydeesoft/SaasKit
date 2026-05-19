<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tests\Unit;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Caydeesoft\SaasKit\Features\Contracts\FeatureRepositoryInterface;
use Caydeesoft\SaasKit\Features\DTOs\FeatureData;
use Caydeesoft\SaasKit\Features\Models\Feature;
use Caydeesoft\SaasKit\Features\Models\PlanFeature;
use Caydeesoft\SaasKit\Features\Services\FeatureGate;

final class FeatureGateTest extends TestCase
{
    public function test_it_checks_plan_feature_status(): void
    {
        $repository = new class implements FeatureRepositoryInterface {
            public function find(int|string $id): ?Feature
            {
                return null;
            }

            public function findByKey(string $key): ?Feature
            {
                return null;
            }

            public function featuresForPlan(int|string $planId): Collection
            {
                return new Collection();
            }

            public function create(FeatureData $data): Feature
            {
                return new Feature($data->toArray());
            }

            public function syncPlanFeature(
                int|string $planId,
                int|string $featureId,
                bool $enabled = true,
                ?int $limit = null,
                array $value = [],
            ): PlanFeature {
                return new PlanFeature();
            }

            public function findPlanFeature(int|string $planId, string $featureKey): ?PlanFeature
            {
                return new PlanFeature([
                    'plan_id' => $planId,
                    'feature_id' => 1,
                    'enabled' => $featureKey === 'projects',
                    'limit' => 10,
                ]);
            }
        };

        $gate = new FeatureGate($repository);

        self::assertTrue($gate->enabledForPlan(1, 'projects'));
        self::assertFalse($gate->enabledForPlan(1, 'exports'));
        self::assertSame(10, $gate->limitForPlan(1, 'projects'));
    }
}
