<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/upload-chunk', [UploadController::class, 'uploadChunk']);
Route::get('/upload-progress/{filename}',[UploadController::class, 'uploadProgress']);
