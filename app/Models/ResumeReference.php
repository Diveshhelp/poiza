<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeReference extends Model
{
     use HasFactory;
    protected $fillable = ['resume_id', 'ref_name'];
}
