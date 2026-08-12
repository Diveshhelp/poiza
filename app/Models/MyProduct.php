<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyProduct extends Model
{
    use HasFactory;

    protected $table = 'my_products';

    protected $fillable = [
        'uuid',
        'product_name',
        'product_code',
        'product_alias',
        'product_category',
        'finish',
        'size',
        'image',
    ];
}