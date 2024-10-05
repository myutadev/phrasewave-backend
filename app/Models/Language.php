<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    public $timestamps = false; 
    public function words()
    {
        return $this->hasMany(Word::class, 'language_code', 'language_code');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
