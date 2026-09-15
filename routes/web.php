<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// API Routes for Multi-Device MySQL Synchronization & Admin Management
Route::prefix('api')->middleware(['throttle:120,1'])->group(function () {
    // Public / App Data
    Route::get('/app-data', [ApiController::class, 'getAppData']);

    // Admin Authentication
    Route::post('/admin/login', [ApiController::class, 'adminLogin'])->middleware('throttle:10,1');
    Route::post('/admin/logout', [ApiController::class, 'adminLogout']);
    Route::get('/admin/check', [ApiController::class, 'adminCheck']);

    // Assessments CRUD
    Route::post('/assessments', [ApiController::class, 'storeAssessment']);
    Route::delete('/assessments/{id}', [ApiController::class, 'deleteAssessment']);

    // Schools CRUD (Protected)
    Route::post('/schools', [ApiController::class, 'storeSchool']);
    Route::delete('/schools/{id}', [ApiController::class, 'deleteSchool']);

    // Materis CRUD (Protected)
    Route::post('/materis', [ApiController::class, 'storeMateri']);
    Route::delete('/materis/{id}', [ApiController::class, 'deleteMateri']);

    // Videos CRUD (Protected)
    Route::post('/videos', [ApiController::class, 'storeVideo']);
    Route::delete('/videos/{id}', [ApiController::class, 'deleteVideo']);

    // FAQs CRUD (Protected)
    Route::post('/faqs', [ApiController::class, 'storeFaq']);
    Route::delete('/faqs/{id}', [ApiController::class, 'deleteFaq']);

    // Researchers CRUD (Protected)
    Route::post('/researchers', [ApiController::class, 'storeResearcher']);
    Route::delete('/researchers/{id}', [ApiController::class, 'deleteResearcher']);

    // Settings (Protected)
    Route::post('/settings', [ApiController::class, 'updateSettings']);
});
