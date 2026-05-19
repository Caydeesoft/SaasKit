<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Subscription extends Model
{
    protected $table = 'saas_kit_subscriptions';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'billable_type',
        'billable_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'provider',
        'provider_subscription_id',
        'metadata',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function usageRecords(): HasMany
    {
        return $this->hasMany(UsageRecord::class, 'subscription_id');
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['active', 'trialing'], true);
    }
}
