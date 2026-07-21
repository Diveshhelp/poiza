<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseLog extends Model
{
    use HasFactory;

    protected $table = 'expenses_logs';

    protected $fillable = [
        'old_content',
        'new_content',
        'team_id',
        'user_id',
        'created_by'
    ];

    protected $casts = [
        'old_content' => 'array',
        'new_content' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}