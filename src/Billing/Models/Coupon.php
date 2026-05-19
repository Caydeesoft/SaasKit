<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Billing\Models;

use Illuminate\Database\Eloquent\Model;

final class Coupon extends Model
{
    protected $table = 'saas_kit_coupons';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'type',
        'value',
        'valid_from',
        'valid_until',
        'max_redemptions',
        'redemptions',
        'metadata',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'metadata' => 'array',
    ];
}
