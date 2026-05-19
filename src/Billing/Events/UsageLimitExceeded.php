<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Events;

use Caydeesoft\SaasKit\Billing\Models\UsageRecord;

final readonly class UsageLimitExceeded
{
    public function __construct(public UsageRecord $usageRecord)
    {
    }
}
