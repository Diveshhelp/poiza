<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DioraProductCostTemplate extends Model
{
    use HasFactory;

    protected $table = 'diora_product_cost_templates';

    protected $fillable = [
        'diora_product_id',
        'mortise_set',
        'pinjaru_dabi',
        'box',
        'packaging',
        'masalo',
        'profit_margin_percent',
        'buff_matt',
        'buff_glossy',
        'pvd_cost',
        'black_color',
        'antique_color',
        'two_pc_matt_black',
        'two_pc_matt_buff',
        'two_pc_matt_bolt',
        'two_pc_pvd_color',
        'two_pc_pvd_buff',
        'two_pc_pvd_bolt',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(DioraProduct::class, 'diora_product_id');
    }
}