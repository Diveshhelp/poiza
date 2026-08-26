<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuffPrice extends Model
{
    use HasFactory;

    protected $table = 'buff_prices';

    protected $fillable = [
        'product_id',
        'piece_number',
        'price_per_piece',
        'pricing_type',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(MyProduct::class, 'product_id');
    }
}