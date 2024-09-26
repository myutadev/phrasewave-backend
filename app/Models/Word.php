<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Word extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'language_code', 'word'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_code', 'language_code');
    }

    public function phrases()
    {
        return $this->belongsToMany(Phrase::class);
    }
}
