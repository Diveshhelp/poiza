<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomerEmailLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'content',
        'recipients_count',
        'status',
        'user_id',
        'attachment',
        'success_count',
        'failure_count',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
