<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ModalityVisualizationController;
use App\Http\Controllers\User;
use App\Http\Controllers\ModalityAuditoryController;
use App\Http\Controllers\ModalityReadingWritingController;
use App\Http\Controllers\ScoresController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ModalityReadingController;
use App\Models\ModalityAuditory;
use App\Models\ModalityKinesthetic;
use App\Models\ModalityReading;
use App\Models\ModalityVisualization;
use App\Models\ModalityWriting;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;

Route::fallback(function () {
    return view('client');
});

Route::post('/auth/login', [AuthController::class, 'generateToken']);
Route::post('/auth/register', [AuthController::class, 'registerUser']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/api/users/me', [User::class, 'me']);
    Route::post('/api/files/upload', [FileController::class, 'uploadFile']);
    Route::get('/api/files/metadata/{id}', [FileController::class, 'getFileMetadata']);
    Route::get('/api/files/{id}', [FileController::class, 'getFile']);
    Route::get('/api/modality/reading/{mode}/{id}',[ModalityReadingController::class,'getReadingTest']);
});
