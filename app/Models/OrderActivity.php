<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderActivity extends Model
{
    protected $fillable = [
        'order_id',
        'activity_type',
        'old_value',
        'new_value',
        'description',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}