<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Permission extends Model
{
    protected $table = 'saas_kit_permissions';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'key',
        'guard_name',
        'metadata',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'saas_kit_role_permissions',
            'permission_id',
            'role_id',
        )->withTimestamps();
    }
}
