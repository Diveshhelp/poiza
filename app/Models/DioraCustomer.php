<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DioraCustomer extends Model
{
    use HasFactory;

    protected $table = 'diora_customers';

    protected $fillable = [
        'uuid',
        'customer_name',
        'company_name',
        'customer_phone',
        'customer_email',
        'billing_address',
        'shipping_address',
        'gstin',
        'city',
        'state',
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
}