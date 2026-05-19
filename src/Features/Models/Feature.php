<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Features\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Feature extends Model
{
    protected $table = 'saas_kit_features';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'key',
        'description',
        'metadata',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    public function planFeatures(): HasMany
    {
        return $this->hasMany(PlanFeature::class, 'feature_id');
    }
}
