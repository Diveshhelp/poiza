<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeAttachment extends Model
{
     use HasFactory;
        protected $fillable = ['resume_id', 'file_name', 'file_path'];

}
