<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'product_id',
        'stock',
        'type',
        'notes',
        'team_id',
        'user_id',
        'created_by'
    ];

    public function product()
    {
        return $this->belongsTo(StockProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}