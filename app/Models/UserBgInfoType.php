<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserBgInfoType extends Model
{
    use HasFactory;

    public function userBackgrounds()
    {
        return $this->HasMany(UserBackground::class);
    }
}
