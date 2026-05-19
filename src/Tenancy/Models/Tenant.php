<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Tenancy\Models;

use Illuminate\Database\Eloquent\Model;

final class Tenant extends Model
{
    protected $table = 'saas_kit_tenants';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'status',
        'metadata',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
