<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Role extends Model
{
    protected $table = 'saas_kit_roles';

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

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'saas_kit_role_permissions',
            'role_id',
            'permission_id',
        )->withTimestamps();
    }
}
