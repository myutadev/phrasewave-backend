<?php

namespace App\Http\Controllers\Myphrases;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestoreDeletedWordRequest;
use App\Services\MyphraseService;
use Exception;
use Illuminate\Support\Facades\Log;

class RestoreDeleteWordController extends Controller
{
    protected $myphraseService;

    public function __construct(MyphraseService $myphraseService)
    {
        $this->myphraseService = $myphraseService;
    }
    public function __invoke(RestoreDeletedWordRequest $request)
    {
        Log::alert('restore started: ' . json_encode($request));

        $validated = $request->validated();

        try {
            $response = $this->myphraseService->restoreSoftDeletedWord($validated['word_id']);
            if ($response) return response()->json(['message' => 'successfully restore the word', 'data' => $response['word_id']], 200);
            return response()->json(['message' => 'the word not found'], 404);
        } catch (\Exception $e) {
            Log::error('データの削除中にエラーが発生しました', ['error' => $e->getMessage()]); // 修正: 配列形式に
            return response()->json(['message' => 'unexpected error occured while restore the word.'], 500);
        }
    }
}
