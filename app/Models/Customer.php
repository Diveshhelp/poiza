<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'company_name',
        'email',
        'phone',
        'gstin',
        'customer_type',
        'credit_limit',
        'website',
        'address',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'credit_limit' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to search customers by keywords.
     */
    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($q, $search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('company_name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('phone', 'like', '%' . $search . '%')
              ->orWhere('gstin', 'like', '%' . $search . '%');
        });
    }
}