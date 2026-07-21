<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'ticket';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false; // Since you're using custom timestamp fields

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'establish_name',
        'nature_of_work_id',
        'branch_id',
        'ticket_unique_no',
        'generated_by',
        'approve_by',
        'ticket_approve_date',
        'work_alloted_to',
        'ticket_close_by',
        'tocket_close_approve_by',
        'ticket_close_approve_date',
        'ticket_transfered_to',
        'ticket_transfered_date',
        'status',
        'json_data',
        'created_at',
        'created_by',
        'updated_at',
        'team_id',
        'user_id',
        'uuid'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'ticket_approve_date' => 'datetime',
        'ticket_close_approve_date' => 'datetime',
        'ticket_transfered_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'json_data' => 'array',
        'status' => 'string',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        // Add any sensitive fields here
    ];

    // Relationships

    /**
     * Get the nature of work that owns the ticket.
     */
    public function natureOfWork()
    {
        return $this->belongsTo(NatureOfWork::class, 'nature_of_work_id');
    }

    /**
     * Get the branch that owns the ticket.
     */
    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    /**
     * Get the user who generated the ticket.
     */
    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * Get the user who approved the ticket.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approve_by');
    }

    /**
     * Get the user to whom work is allotted.
     */
    public function workAllottedTo()
    {
        return $this->belongsTo(User::class, 'work_alloted_to');
    }

    /**
     * Get the user who closed the ticket.
     */
    public function ticketClosedBy()
    {
        return $this->belongsTo(User::class, 'ticket_close_by');
    }

    /**
     * Get the user who approved ticket closure.
     */
    public function ticketCloseApprovedBy()
    {
        return $this->belongsTo(User::class, 'tocket_close_approve_by');
    }

    /**
     * Get the user to whom ticket was transferred.
     */
    public function ticketTransferredTo()
    {
        return $this->belongsTo(User::class, 'ticket_transfered_to');
    }

    /**
     * Get the user who created the ticket.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes

    /**
     * Scope a query to only include active tickets.
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    /**
     * Scope a query to only include inactive tickets.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', '0');
    }

    /**
     * Scope a query to filter by branch.
     */
    public function scopeByBranches($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    // Accessors & Mutators

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return $this->status === '1' ? 'Active' : 'Inactive';
    }

    /**
     * Check if ticket is active.
     */
    public function getIsActiveAttribute()
    {
        return $this->status === '1';
    }

    /**
     * Check if ticket is closed.
     */
    public function getIsClosedAttribute()
    {
        return !is_null($this->ticket_close_approve_date);
    }

    /**
     * Check if ticket is transferred.
     */
    public function getIsTransferredAttribute()
    {
        return !is_null($this->ticket_transfered_to);
    }
}