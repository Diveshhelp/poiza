<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeFollowup extends Model
{
     use HasFactory;
        protected $fillable = ['resume_id', 'followup_date', 'note'];

}
