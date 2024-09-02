<?php

namespace App\Http\Controllers\Myphrases;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMyphraseRequest;
use App\Services\MyphraseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CreateMyphraseController extends Controller
{
    protected $myphraseService;

    public function __construct(MyphraseService $myphraseService)
    {
        $this->myphraseService = $myphraseService;
    }
    public function __invoke(CreateMyphraseRequest $request): JsonResponse
    {
        try {
            $newPhraseData = $request->validated();
            $savedIds = $this->myphraseService->createMyphrase($newPhraseData);
            return response()->json([
                'message' => 'データが正常に保存されました',
                'data' => $savedIds
            ], 200);
        } catch (\Exception $e) {
            Log::error('データの保存中にエラーが発生しました', $e->getMessage());
            return response()->json([
                'message' => 'データの保存に失敗しました'
            ], 500);
        }
    }
}
