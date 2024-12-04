<?php

use App\Http\Controllers\FilesController;
use App\Http\Controllers\ModalityVisualizationController;
use App\Http\Controllers\User;
use App\Http\Controllers\ModalityAuditoryController;
use App\Http\Controllers\ModalityReadingWritingController;
use App\Http\Controllers\ScoresController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'users' => User::class,
    'files' => FilesController::class,
    'modality/visualizations' => ModalityVisualizationController::class,
    'modality/reading-writing' => ModalityReadingWritingController::class,
    'modality/auditory' => ModalityAuditoryController::class,
    'scores' => ScoresController::class,
]);
