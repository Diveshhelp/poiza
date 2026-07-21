<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyDocuments extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_title',
        'other_info',
        'team_id',
        'user_id',
    ];

    /**
     * Get the user that owns the document.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team that the document belongs to.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
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