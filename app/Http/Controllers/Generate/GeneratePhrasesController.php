<?php

namespace App\Http\Controllers\Generate;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateRequest;
use App\Services\GeneratePhraseService;
use Illuminate\Support\Facades\Log;

class GeneratePhrasesController extends Controller
{
    protected $generatePhraseService;

    public function __construct(GeneratePhraseService $generatePhraseService)
    {
        $this->generatePhraseService = $generatePhraseService;
    }


    public function __invoke(GenerateRequest $request)
    {

        try {
            $res = $this->generatePhraseService->generatePhrase($request);
            return response()->json($res);
        } catch (\Exception $e) {
            Log::error('Error generating phrases: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
