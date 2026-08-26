<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class MyProduct extends Model
{
    use HasFactory;

    protected $table = 'my_products';

    protected $fillable = [
        'uuid',
        'product_name',
        'product_code',
        'product_alias',
        'category_id',
        'category_name',
        'finish',
        'size',
        'price',
        'packing',
        'type_of_model',
        'material',
        'model_key',
        'piece',
        'image',
    ];

    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function buffPrices(): HasMany
    {
        return $this->hasMany(BuffPrice::class, 'product_id');
    }
    
}