<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    public function words()
    {
        return $this->hasMany(Word::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
