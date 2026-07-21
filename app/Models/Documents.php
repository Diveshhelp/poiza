<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documents extends Model
{
    use HasFactory, SoftDeletes;
    use SoftDeletes;
    
    protected $fillable = [
        'uuid',
        'ownership_name',
        'department_id',
        'doc_title',
        'doc_name',
        'doc_categories_id',
        'doc_validity',
        'doc_renewal_dt',
        'doc_update_date',
        'doc_file',
        'doc_note',
        'parent_id',
        'doc_year',
        'doc_number',
        'doc_info',
        'created_by',
        'doc_expire_date',
        'share_with_firm',
        'is_completed','team_id'
    ];

    protected $table = 'documents'; 

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(DocAttachment::class, 'documents_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'ownership_name', 'id');
    }
    public function subDocuments()
    {
        return $this->hasMany(Documents::class, 'parent_id');
    }

    public function subattachments()
    {
        return $this->hasMany(DocAttachment::class, 'documents_id', 'id');
    }
    public function emailLogs()
    {
        return $this->hasMany(EmailLogs::class);
    }
    public function category()
    {
        return $this->belongsTo(DocCategory::class, 'doc_categories_id');
    }
    public function doc_categories()
    {
        return $this->belongsTo(DocCategory::class, 'doc_categories_id');
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
    public function ownership()
    {
        return $this->belongsTo(Ownership::class, 'ownership_name', 'id');
    }
    public function parent()
    {
        return $this->belongsTo(Documents::class, 'parent_id');
    }

    public function notes()
    {
        return $this->hasMany(DocumentNotes::class)->orderBy('created_at','DESC');
    }
    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}