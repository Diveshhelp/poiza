<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'uuid',
        'name',
        'sku',
        'category_id',
        'cost_price',
        'selling_price',
        'stock_quantity',
        'alert_quantity',
        'unit',
        'description',
        'status',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}