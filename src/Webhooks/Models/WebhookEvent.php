<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Webhooks\Models;

use Illuminate\Database\Eloquent\Model;

final class WebhookEvent extends Model
{
    protected $table = 'saas_kit_webhook_events';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'provider',
        'event_type',
        'provider_event_id',
        'payload',
        'headers',
        'status',
        'failure_reason',
        'received_at',
        'processed_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
    ];
}
