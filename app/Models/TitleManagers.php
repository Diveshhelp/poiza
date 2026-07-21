<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleManagers extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type_title',
        'title_status',
        'uuid',
        'title_collection_id',
        'created_by'
    ];


    public function created_user(){
        return $this->belongsTo(User::class,'created_by');
    }
    
    public function titleValues()
    {
        return $this->hasMany(TitleValueManagers::class,'title_manager_id');
    }
}
