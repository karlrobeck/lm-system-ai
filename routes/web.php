<?php

use App\Http\Controllers\FilesController;
use App\Http\Controllers\ModalityVisualizationController;
use App\Http\Controllers\User;
use App\Http\Controllers\ModalityAuditoryController;
use App\Http\Controllers\ModalityReadingWritingController;
use App\Http\Controllers\ScoresController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'api/users' => User::class,
    'api/files' => FilesController::class,
    'api/modality/visualizations' => ModalityVisualizationController::class,
    'api/modality/reading-writing' => ModalityReadingWritingController::class,
    'api/modality/auditory' => ModalityAuditoryController::class,
    'api/scores' => ScoresController::class,
]);

Route::fallback(function () {
    return view('client');
});
