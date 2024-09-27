<?php

namespace App\Http\Controllers\Myphrases;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestoreDeletedWordRequest;
use App\Services\MyphraseService;
use Exception;

class RestoreDeleteWordController extends Controller
{
    protected $myphraseService;

    public function __construct(MyphraseService $myphraseService)
    {
        $this->myphraseService = $myphraseService;
    }
    public function __invoke(RestoreDeletedWordRequest $request)
    {

        $validated = $request->validated();

        try {
            $response = $this->myphraseService->restoreSoftDeletedWord($validated['word_id']);
            if ($request) return response()->json(['message' => 'successfully restore the word', 'data' => $response['word_id']], 200);
            return response()->json(['message' => 'the word not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'unexprected error occured while restore the word'], 500);
        }
    }
}
