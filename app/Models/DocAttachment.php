<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DocAttachment extends Model
{
    protected $fillable = [
        'uuid',
        'documents_id',
        'file_name',
        'original_file_name',
        'file_path',
        'file_type',
        'file_size',
        'is_parent'
    ];

    protected $table = 'doc_attachments';

    public function document()
    {
        return $this->belongsTo(Documents::class, 'documents_id', 'id');
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
}