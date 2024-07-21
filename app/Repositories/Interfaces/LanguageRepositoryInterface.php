<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface LanguageRepositoryInterface
{
    public function getAll(): Collection;
}
