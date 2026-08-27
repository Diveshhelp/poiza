<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DioraOrderItem extends Model
{
    use HasFactory;

    protected $table = 'diora_order_items';

    protected $fillable = [
        'diora_order_id',
        'diora_product_id',
        'quantity',
        'price',
        'total',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(DioraProduct::class, 'diora_product_id');
    }
}