<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockSale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'product_id',
        'customer_id',
        'price',
        'quantity',
        'total_amount',
        'invoice_number',
        'payment_status',
        'paid_amount',
        'sale_date',
        'notes',
        'status',
        'team_id',
        'user_id',
        'created_by'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2'
    ];

    public function product()
    {
        return $this->belongsTo(StockProduct::class, 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo(StockCustomer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}