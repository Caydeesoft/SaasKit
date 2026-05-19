<?php

declare(strict_types=1);

namespace Caydeesoft\SaasKit\Invoices\Models;

use Illuminate\Database\Eloquent\Model;

final class Invoice extends Model
{
    protected $table = 'saas_kit_invoices';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'customer_type',
        'customer_id',
        'subscription_id',
        'currency',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'lines',
        'status',
        'due_at',
        'paid_at',
        'provider_payment_id',
        'metadata',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'lines' => 'array',
        'due_at' => 'datetime',
        'paid_at' => 'datetime',
        'metadata' => 'array',
    ];
}
