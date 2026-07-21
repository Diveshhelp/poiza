<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropboxDeletionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_path',
        'item_name',
        'item_type',
        'item_size',
        'metadata',
        'ip_address',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}