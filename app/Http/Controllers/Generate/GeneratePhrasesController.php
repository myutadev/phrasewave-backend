<?php

namespace App\Http\Controllers\Generate;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateRequest;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

class GeneratePhrasesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GenerateRequest $request)
    {
        $formData = $request->json()->all();

        Log::info('formData is', $formData);
        Log::info('Request details', [
            // 'headers' => $request->headers->all(),
            // 'content' => $request->getContent(),
            'request' => $request
        ]);

        return [
            [
                "usedWords" => ["obsolete", "overload"],
                "generatedPhrase" => "If we overload the system with obsolete technology, it may fail completely."
            ]
        ];
    }
}
