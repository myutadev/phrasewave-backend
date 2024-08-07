<?php

use App\Http\Controllers\Generate\GeneratePhrasesController;
use App\Http\Controllers\Languages\GetAllLanguagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/languages', GetAllLanguagesController::class);

Route::post('/generate', GeneratePhrasesController::class);

Route::post('/myphrases', GeneratePhrasesController::class);
Route::delete('/myphrases', GeneratePhrasesController::class);

Route::get('/csrf-token', function (Request $request) {
    return response()->json(['token' => csrf_token()]);
});
