<?php

namespace App\Repositories;

use App\Models\Language;
use App\Repositories\Interfaces\LanguageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LanguageRepository implements LanguageRepositoryInterface
{

    protected Language $language;

    public function __construct(Language $language)
    {
        $this->language = $language;
    }

    public function getAll(): Collection
    {
        return $this->language->all();
    }
}
