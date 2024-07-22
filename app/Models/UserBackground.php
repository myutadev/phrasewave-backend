<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBackground extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'user_bg_info_type_id', 'description'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userBgInfoType()
    {
        return $this->belongsTo(UserBgInfoType::class);
    }
}
