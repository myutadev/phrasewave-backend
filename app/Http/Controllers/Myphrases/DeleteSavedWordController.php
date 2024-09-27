<?php

namespace App\Http\Controllers\Myphrases;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteSavedWordRequest;
use App\Services\MyphraseService;
use Exception;
use Illuminate\Http\Request;

class DeleteSavedWordController extends Controller
{

    protected $myphraseService;

    public function __construct(MyphraseService $myphraseService)
    {
        $this->myphraseService = $myphraseService;
    }

    public function __invoke(DeleteSavedWordRequest $request)
    {
        $validated = $request->validated();
        try {

            $deletedWord = $this->myphraseService->deleteSavedWord($validated['word_id']);
            if ($deletedWord) return response()->json(['message' => 'the word deleted successfully.', 'data' => $deletedWord], 200);
            return response()->json(['message' => 'the word not found'], 404);
        } catch (Exception $e) {

            return response()->json(['message' => 'unexpected error occured while deleting word'], 500);
        }
    }
}
