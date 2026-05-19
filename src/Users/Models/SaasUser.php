<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class SaasUser extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = 'saas_kit_users';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'status',
        'metadata',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }
}
