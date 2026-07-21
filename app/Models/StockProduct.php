<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class StockProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'product_name',
        'category_id',
        'description',
        'price',
        'quantity',
        'sku',
        'images',
        'status',
        'team_id',
        'user_id',
        'created_by'
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(StockCategory::class, 'category_id');
    }

    // public function getImageUrl($filename)
    // {
    //     if (empty($filename)) {
    //         return asset('images/placeholder.png');
    //     }

    //     $storagePath = Storage::disk('public')->path('product-images');
    //     $files = glob($storagePath . '/*_' . $filename);
        
    //     if (!empty($files)) {
    //         $relativePath = str_replace(Storage::disk('public')->path(''), '', $files[0]);
    //         return Storage::url($relativePath);
    //     }
        
    //     return asset('images/placeholder.png');
    // }
    public function getImageUrl($filename)
    {
        $storagePath = Storage::disk('public')->path('product-images');
        $files = glob($storagePath . '/*_' . $filename);
        if (!empty($files)) {
            $relativePath = str_replace(Storage::disk('public')->path(''), '', $files[0]);
            return Storage::url($relativePath);
        }
        return asset('images/placeholder.png');
    }
}