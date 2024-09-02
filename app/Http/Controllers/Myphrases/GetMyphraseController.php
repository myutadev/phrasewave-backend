<?php

namespace App\Http\Controllers\Myphrases;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetMyphraseRequest;
use App\Services\MyphraseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetMyphraseController extends Controller
{
    protected $myphraseServce;

    public function __construct(MyphraseService $myphraseService)
    {
        $this->myphraseServce = $myphraseService;
    }


    public function __invoke(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $responseArray = $this->myphraseServce->getAllSavedPhrasesByUser($userId);
            return response()->json([
                'message' => 'データの取得に成功しました',
                'data' => $responseArray
            ], 200);
        } catch (\Exception $e) {
            Log::error('Myphrase データの取得中にエラーが発生しました', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => "データの取得中にエラーが発生しました",
            ], 500);
        }
    }
}
