<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Features\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Caydeesoft\SaasKit\Billing\Models\Plan;

final class PlanFeature extends Model
{
    protected $table = 'saas_kit_plan_features';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'plan_id',
        'feature_id',
        'enabled',
        'limit',
        'value',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'enabled' => 'boolean',
        'value' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }
}
