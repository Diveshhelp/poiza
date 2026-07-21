<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAttachments extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'email_attachments';
    protected $fillable = [
        'email_logs_id',
        'attachment_id',
        'file_name',
        'file_size',
    ];

    /**
     * Get the email log this attachment belongs to.
     */
    public function emailLog()
    {
        return $this->belongsTo(EmailLogs::class);
    }

}