<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Events;

use Caydeesoft\SaasKit\Audit\Events\AuditableEventInterface;
use Caydeesoft\SaasKit\Billing\Models\Subscription;

final readonly class SubscriptionStarted implements AuditableEventInterface
{
    public function __construct(public Subscription $subscription)
    {
    }

    public function auditPayload(): array
    {
        return [
            'event' => 'subscription.started',
            'subject_type' => $this->subscription::class,
            'subject_id' => (string) $this->subscription->getKey(),
            'metadata' => ['plan_id' => $this->subscription->plan_id],
        ];
    }
}
