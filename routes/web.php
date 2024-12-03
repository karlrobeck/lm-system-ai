<?php

use App\Http\Controllers\FilesController;
use App\Http\Controllers\ModalityVisualizationController;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', User::class)->name('*', 'users');
Route::apiResource('files', FilesController::class)->name('*', 'files');
Route::apiResource('modality/visualizations', ModalityVisualizationController::class)->name('*', 'modality_visualizations');
