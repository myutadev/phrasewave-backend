<?php

namespace App\Http\Controllers\Myphrases;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteMyphraseRequest;
use App\Services\MyphraseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DeleteMyphraseController extends Controller
{
    protected $myphraseService;

    public function __construct(MyphraseService $myphraseService)
    {
        $this->myphraseService = $myphraseService;
    }

    public function __invoke(DeleteMyphraseRequest $request): JsonResponse
    {
        try {
            $deleteData = $request->validated();
            $this->myphraseService->deleteMyphrase($deleteData);
            return response()->json([
                'message' => 'データの削除に成功しました。'
            ], 200);
        } catch (\Exception $e) {
            Log::error('データの削除中にエラーが発生しました', $e->getMessage());
            return response()->json(['message' => 'データの削除中にエラーが発生しました'], 500);
        }
    }
}
