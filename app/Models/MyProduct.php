<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'finish',
        'size',
        'image',
        'piece'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}