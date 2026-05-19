<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Repositories;

use Illuminate\Support\Collection;
use Caydeesoft\SaasKit\Billing\Contracts\SubscriptionRepositoryInterface;
use Caydeesoft\SaasKit\Billing\DTOs\SubscriptionData;
use Caydeesoft\SaasKit\Billing\Models\Subscription;

final class EloquentSubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function find(int|string $id): ?Subscription
    {
        return Subscription::query()->find($id);
    }

    public function activeForBillable(string $billableType, int|string $billableId): ?Subscription
    {
        return Subscription::query()
            ->where('billable_type', $billableType)
            ->where('billable_id', (string) $billableId)
            ->whereIn('status', ['active', 'trialing'])
            ->latest('id')
            ->first();
    }

    public function forBillable(string $billableType, int|string $billableId): Collection
    {
        return Subscription::query()
            ->where('billable_type', $billableType)
            ->where('billable_id', (string) $billableId)
            ->latest('id')
            ->get();
    }

    public function create(SubscriptionData $data): Subscription
    {
        return Subscription::query()->create($data->toArray());
    }

    public function update(Subscription $subscription, array $attributes): Subscription
    {
        $subscription->fill($attributes);
        $subscription->save();

        return $subscription->refresh();
    }

    public function cancel(Subscription $subscription, bool $immediately = false): Subscription
    {
        $subscription->fill([
            'status' => $immediately ? 'cancelled' : 'cancelling',
            'ends_at' => $immediately ? now() : $subscription->ends_at,
        ]);
        $subscription->save();

        return $subscription->refresh();
    }
}
