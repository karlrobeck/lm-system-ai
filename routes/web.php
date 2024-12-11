<?php

use App\Http\Controllers\FilesController;
use App\Http\Controllers\ModalityVisualizationController;
use App\Http\Controllers\User;
use App\Http\Controllers\ModalityAuditoryController;
use App\Http\Controllers\ModalityReadingWritingController;
use App\Http\Controllers\ScoresController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;

Route::fallback(function () {
    return view('client');
});

Route::post('/auth/login', [AuthController::class, 'generateToken']);
Route::post('/auth/register', [AuthController::class, 'registerUser']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('api/users/me', [User::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::apiResources([
        'api/users' => User::class,
        'api/files' => FilesController::class,
        'api/modality/visualizations' => ModalityVisualizationController::class,
        'api/modality/reading-writing' => ModalityReadingWritingController::class,
        'api/modality/auditory' => ModalityAuditoryController::class,
        'api/scores' => ScoresController::class,
    ]);
    Route::get('api/modality/visualizations/context-file/{id}', [ModalityVisualizationController::class, 'showByContextFile']);
    Route::get('api/modality/auditory/context-file/{id}', [ModalityAuditoryController::class, 'showByContextFile']);
    Route::get('api/modality/reading-writing/context-file/{id}', [ModalityReadingWritingController::class, 'showByContextFile']);
});
