<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuffPiece extends Model
{
    use HasFactory;

    protected $table = 'buff_pieces';

    protected $fillable = [
         'order_id',
        'order_item_id',
        'product_id',
        'piece_number',
        'order_qty',
        'received_qty',
        'price_per_unit',
        'total_amount',
        'pricing_type',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(MyProduct::class, 'product_id'); // Pointing to your MyProduct model
    }
}