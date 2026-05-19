<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class UsageRecord extends Model
{
    protected $table = 'saas_kit_usage_records';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'subscription_id',
        'feature_key',
        'quantity',
        'occurred_at',
        'metadata',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'occurred_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
