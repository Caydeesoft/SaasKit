<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Repositories;

use Caydeesoft\SaasKit\Billing\Contracts\UsageRepositoryInterface;
use Caydeesoft\SaasKit\Billing\DTOs\UsageRecordData;
use Caydeesoft\SaasKit\Billing\Models\UsageRecord;

final class EloquentUsageRepository implements UsageRepositoryInterface
{
    public function record(UsageRecordData $data): UsageRecord
    {
        return UsageRecord::query()->create($data->toArray());
    }

    public function sumForSubscriptionFeature(int|string $subscriptionId, string $featureKey): int
    {
        return (int) UsageRecord::query()
            ->where('subscription_id', $subscriptionId)
            ->where('feature_key', $featureKey)
            ->sum('quantity');
    }
}
