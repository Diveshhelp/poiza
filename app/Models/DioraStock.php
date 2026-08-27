<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DioraStock extends Model
{
    use HasFactory;

    protected $table = 'diora_stocks';

    protected $fillable = [
        'diora_product_id',
        'quantity',
        'type',
        'reference_no',
        'notes',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(DioraProduct::class, 'diora_product_id');
    }
}