<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocSelections extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'user_id',
        'team_id',
        'created_by',
        'doc_status'
    ];
}
