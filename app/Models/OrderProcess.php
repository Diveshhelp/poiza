<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'casting_status',
        'casting_completed_at',
        'turning_status',
        'turning_completed_at',
        'buff_status',
        'buff_completed_at',
        'overall_status',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}