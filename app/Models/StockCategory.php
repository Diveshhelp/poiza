<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'category_name',
        'description',
        'status',
        'team_id',
        'user_id',
        'created_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}