<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TodoAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'todo_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size'
    ];

    /**
     * Get the todo that owns the attachment.
     */
    public function todo(): BelongsTo
    {
        return $this->belongsTo(Todo::class);
    }
}