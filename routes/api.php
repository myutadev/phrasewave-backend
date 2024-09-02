<?php

use App\Http\Controllers\Generate\GeneratePhrasesController;
use App\Http\Controllers\Languages\GetAllLanguagesController;
use App\Http\Controllers\Myphrases\CreateMyphraseController;
use App\Http\Controllers\Myphrases\DeleteMyphraseController;
use App\Http\Controllers\Myphrases\GetMyphraseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/myphrases', CreateMyphraseController::class);
    Route::delete('/myphrases', DeleteMyphraseController::class);
    Route::get('/myphrases', GetMyphraseController::class);
});




Route::get('/languages', GetAllLanguagesController::class);
Route::post('/generate', GeneratePhrasesController::class);


Route::get('/csrf-token', function (Request $request) {
    return response()->json(['token' => csrf_token()]);
});
