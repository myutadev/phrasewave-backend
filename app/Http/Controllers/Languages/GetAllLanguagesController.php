<?php

namespace App\Http\Controllers\Languages;

use App\Http\Controllers\Controller;
use App\Services\LanguageService;
use Illuminate\Http\JsonResponse;

class GetAllLanguagesController extends Controller
{
    protected LanguageService $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function __invoke(): JsonResponse
    {
        $languages = $this->languageService->getAll();
        return response()->json($languages);
    }
}
