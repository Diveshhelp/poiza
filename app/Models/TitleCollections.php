<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleCollections extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_set_name',
        'team_id',
        'title_set_status',
        'uuid',
        'created_by',
    ];

    public function created_user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function collection_set()
    {
        return $this->hasMany(TitleManagers::class,'title_collection_id','id');
   
    }
}
