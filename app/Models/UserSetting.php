<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = ['user_id','dark_mode','preferred_landing'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

