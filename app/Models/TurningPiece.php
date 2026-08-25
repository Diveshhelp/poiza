<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TurningPiece extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'piece_number',
        'order_qty',
        'received_qty',
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}