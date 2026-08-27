<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DioraOrder extends Model
{
    use HasFactory;

    protected $table = 'diora_orders';

    protected $fillable = [
        'order_no',
        'diora_customer_id',
        'status',
        'total_amount',
        'order_date',
        'notes',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(DioraCustomer::class, 'diora_customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DioraOrderItem::class, 'diora_order_id');
    }
}