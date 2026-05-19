<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class UserProfile extends Model
{
    protected $table = 'saas_kit_user_profiles';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'display_name',
        'timezone',
        'locale',
        'attributes',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'attributes' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(SaasUser::class, 'user_id');
    }
}
