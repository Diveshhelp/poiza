<?php

namespace App\Models;

use App\Livewire\Photo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size'
    ];

    /**
     * Get the todo that owns the attachment.
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}