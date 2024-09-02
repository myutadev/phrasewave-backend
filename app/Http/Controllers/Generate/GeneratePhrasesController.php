<?php

namespace App\Http\Controllers\Generate;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateRequest;
use App\Services\GeneratePhraseService;
use Illuminate\Database\Eloquent\Collection;
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
            // $res = $this->generatePhraseService->generatePhrase($request);
            // テスト用に固定テキストを返すようにする
            sleep(1);
            $testData = [
                [
                    "usedWords" => ["overload", "system"],
                    "generatedPhrase" => "The system may experience an overload during peak hours."
                ],
                [
                    "usedWords" => ["overload"],
                    "generatedPhrase" => "To prevent an overload, we need to manage our resources efficiently."
                ],
                [
                    "usedWords" => ["overload"],
                    "generatedPhrase" => "An overload could lead to failures in the machinery."
                ]
            ];
            $res = new Collection($testData);
            return response()->json($res);
        } catch (\Exception $e) {
            Log::error('Error generating phrases: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
