<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLogs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'documents_id',
        'recipient_email',
        'subject',
        'message',
        'sent_at',
        'sent_by',
        'attachments_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Get the document that this email was sent for.
     */
    public function document()
    {
        return $this->belongsTo(Documents::class);
    }

    /**
     * Get the user who sent the email.
     */
    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Get the email attachments.
     */
    public function emailAttachments()
    {
        return $this->hasMany(EmailAttachments::class);
    }
    protected function serializeDate(\DateTimeInterface $date)
     {
         return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))->format('Y-m-d h:i A');
     }
     public function getCreatedAtAttribute($value)
     {
         if ($value) {
             return Carbon::parse($value)->setTimezone('Asia/Kolkata');
         }
         return $value;
     }
     
     public function getUpdatedAtAttribute($value)
     {
         if ($value) {
             return Carbon::parse($value)->setTimezone('Asia/Kolkata');
         }
         return $value;
     }
     public function getSentAtAttribute($value)
     {
         if ($value) {
             return Carbon::parse($value)->setTimezone('Asia/Kolkata');
         }
         return $value;
     }
}