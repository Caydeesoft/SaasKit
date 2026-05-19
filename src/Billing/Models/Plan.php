<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Plan extends Model
{
    protected $table = 'saas_kit_plans';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'key',
        'interval',
        'amount',
        'currency',
        'trial_days',
        'features',
        'limits',
        'active',
        'metadata',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'features' => 'array',
        'limits' => 'array',
        'active' => 'boolean',
        'metadata' => 'array',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}
