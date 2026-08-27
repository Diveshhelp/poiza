<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DioraProduct extends Model
{
    use HasFactory;

    protected $table = 'diora_products';

    protected $fillable = [
        'uuid',
        'product_name',
        'product_code',
        'product_alias',
        'category_id',
        'category_name',
        'model_key',
        'finish',
        'size',
        'images',
        'piece',
        'packing',
        'price',
        'type_of_model',
        'material',
    ];

    protected $casts = [
        'images' => 'array', // Automatically casts JSON column to array for easy handling
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
    
    public function stocks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DioraStock::class, 'diora_product_id');
    }
    public function costTemplate()
{
    return $this->hasOne(DioraProductCostTemplate::class, 'diora_product_id');
}
}