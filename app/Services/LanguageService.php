<?php

namespace App\Services;

use App\Repositories\LanguageRepository;
use Illuminate\Database\Eloquent\Collection;

class LanguageService
{

    protected LanguageRepository $languageRepository;

    public function  __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function getAll(): Collection
    {
        return  $this->languageRepository->getAll();
    }
}
