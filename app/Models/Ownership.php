<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ownership extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['owner_title','team_id'];

    public function documents()
    {
        return $this->hasMany(Documents::class, 'ownership_name', 'id');
    }
}