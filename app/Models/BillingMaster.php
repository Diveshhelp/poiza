<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'billing_masters';

    protected $fillable = [
        'uuid',
        'team_id',
        'selected_team_id',
        'billing_details_id',
        'invoice_matter',
        'status',
        'amount',
        'billing_start_date',
        'billing_end_date',
        'cancelled_reason',
        'created_by'
    ];

    protected $casts = [
        'billing_start_date' => 'date',
        'billing_end_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function billingDetail()
    {
        return $this->belongsTo(BillingDetail::class, 'billing_details_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function selectedTeam()
    {
        return $this->belongsTo(Team::class, 'selected_team_id');
    }
}