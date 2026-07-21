<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockCustomer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'notes',
        'status',
        'team_id',
        'user_id',
        'created_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}