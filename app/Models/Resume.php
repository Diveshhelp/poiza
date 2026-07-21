<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
     use HasFactory;
     protected $fillable = [
        'uuid', 'candidate_name', 'email', 'candidate_code', 'mobile', 'referance_id',
        'interview_time', 'current_ctc', 'expected_ctc', 'notice_period', 'domain',
        'other_info', 'year_of_exp', 'status', 'created_by', 'team_id'
    ];

    public function attachments() {
        return $this->hasMany(ResumeAttachment::class, 'resume_id');
    }
    public function followups() {
        return $this->hasMany(ResumeFollowup::class, 'resume_id');
    }
    public function references() {
        return $this->hasMany(ResumeReference::class, 'resume_id');
    }

}
