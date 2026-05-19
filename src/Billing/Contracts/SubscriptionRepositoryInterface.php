<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Contracts;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Billing\DTOs\SubscriptionData;
use Caydeesoft\SaasKit\Billing\Models\Subscription;

interface SubscriptionRepositoryInterface
{
    public function find(int|string $id): ?Subscription;

    public function activeForBillable(string $billableType, int|string $billableId): ?Subscription;

    /**
     * @return Collection<int, Subscription>
     */
    public function forBillable(string $billableType, int|string $billableId): Collection;

    public function create(SubscriptionData $data): Subscription;

    /**
     * @param array<string, mixed> $attributes
     */
    public function update(Subscription $subscription, array $attributes): Subscription;

    public function cancel(Subscription $subscription, bool $immediately = false): Subscription;
}
