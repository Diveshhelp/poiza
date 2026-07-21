<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get users that have this role
     */
    public function users()
    {
        return User::whereRaw("FIND_IN_SET(?, user_role)", [$this->id]);
    }

    /**
     * Create a slug from the role name
     *
     * @param string $value
     * @return void
     */
    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = $value;
    //     $this->attributes['slug'] = \Str::slug($value);
    // }
}