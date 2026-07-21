<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'team_id',
        'gst_number',
        'billing_address',
        'joining_date',
        'status'
    ];

    protected $casts = [
        'joining_date' => 'date'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function billingMasters()
    {
        return $this->hasMany(BillingMaster::class, 'billing_details_id');
    }
}