<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use ESolution\DBEncryption\Traits\EncryptedAttribute;
class CalendarEvent extends Model
{
    use HasFactory;
    use EncryptedAttribute;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'date',
        'type',
        'user_id',
        'team_id'
    ];
    protected $encryptable = [
        'title', 'description'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date:Y-m-d', // Explicitly format as YYYY-MM-DD
    ];
    
    /**
     * Get the user that owns the event.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Accessor for the date attribute to ensure it's always in Y-m-d format
     * This is an alternative to the date cast above
     */
    public function getDateAttribute($value)
    {
        // If somehow we still get dates with time part, strip it
        if ($value && strpos($value, 'T') !== false) {
            return substr($value, 0, 10);
        }
        
        // Otherwise, ensure it's in Y-m-d format
        if ($value) {
            return Carbon::parse($value)->format('Y-m-d');
        }
        
        return $value;
    }
    
    /**
     * Mutator for the date attribute to ensure it's stored properly
     */
    public function setDateAttribute($value)
    {
        // Normalize date input before saving to database
        if (is_string($value) && strpos($value, 'T') !== false) {
            $value = substr($value, 0, 10);
        }
        
        $this->attributes['date'] = $value;
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