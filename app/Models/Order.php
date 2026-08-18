<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'order_number',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function activities()
{
    return $this->hasMany(OrderActivity::class)->latest();
}
}