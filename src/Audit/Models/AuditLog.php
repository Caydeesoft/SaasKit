<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Audit\Models;

use Illuminate\Database\Eloquent\Model;

final class AuditLog extends Model
{
    protected $table = 'saas_kit_audit_logs';

    public $timestamps = false;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'event',
        'actor_type',
        'actor_id',
        'subject_type',
        'subject_id',
        'tenant_id',
        'metadata',
        'occurred_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];
}
