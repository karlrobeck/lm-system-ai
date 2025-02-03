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
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ModalityKinestheticController;
use App\Http\Controllers\ModalityReadingController;
use App\Http\Controllers\ModalityWritingController;
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
    Route::get('/api/modality/writing/{mode}/{id}',[ModalityWritingController::class,'getWritingTest']);
    Route::get('/api/modality/auditory/{mode}/{id}',[ModalityAuditoryController::class,'getAuditoryTest']);
    Route::get('/api/modality/kinesthetic/{mode}/{id}',[ModalityKinestheticController::class,'getKinestheticTest']);
    Route::get('/api/modality/visualization/{mode}/{id}',[ModalityVisualizationController::class,'getVisualizationTest']);
    // Route for fetching visualization tests
    Route::get('/visualization-test/{mode}/{id}', [ModalityVisualizationController::class, 'getVisualizationTest']);
    Route::get('/api/assessment/ranking',[AssessmentController::class,'ranking']);
    Route::post("/api/assessment/submit",[AssessmentController::class,'store']);
    Route::get('/api/scores/',[ScoresController::class,'index']);
    Route::get('/api/scores/{id}',[ScoresController::class,'show']);
    Route::post('/api/scores/submit',[ScoresController::class,'show']);
});
