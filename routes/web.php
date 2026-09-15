<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// API Routes for Multi-Device MySQL Synchronization
Route::prefix('api')->group(function () {
    Route::get('/app-data', [ApiController::class, 'getAppData']);

    // Assessments CRUD
    Route::post('/assessments', [ApiController::class, 'storeAssessment']);
    Route::delete('/assessments/{id}', [ApiController::class, 'deleteAssessment']);

    // Schools CRUD
    Route::post('/schools', [ApiController::class, 'storeSchool']);
    Route::delete('/schools/{id}', [ApiController::class, 'deleteSchool']);

    // Materis CRUD
    Route::post('/materis', [ApiController::class, 'storeMateri']);
    Route::delete('/materis/{id}', [ApiController::class, 'deleteMateri']);

    // Videos CRUD
    Route::post('/videos', [ApiController::class, 'storeVideo']);
    Route::delete('/videos/{id}', [ApiController::class, 'deleteVideo']);

    // FAQs CRUD
    Route::post('/faqs', [ApiController::class, 'storeFaq']);
    Route::delete('/faqs/{id}', [ApiController::class, 'deleteFaq']);

    // Researchers CRUD
    Route::post('/researchers', [ApiController::class, 'storeResearcher']);
    Route::delete('/researchers/{id}', [ApiController::class, 'deleteResearcher']);

    // Settings
    Route::post('/settings', [ApiController::class, 'updateSettings']);
});
