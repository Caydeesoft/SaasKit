<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Contracts;

use Caydeesoft\SaasKit\Billing\DTOs\UsageRecordData;
use Caydeesoft\SaasKit\Billing\Models\UsageRecord;

interface UsageRepositoryInterface
{
    public function record(UsageRecordData $data): UsageRecord;

    public function sumForSubscriptionFeature(int|string $subscriptionId, string $featureKey): int;
}
