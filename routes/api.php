<?php

// Auth routes
use App\Http\Controllers\Api\V1\Admin\DrugsApiController;

Route::post('register', 'Api\\AuthController@register');
Route::post('login', 'Api\\AuthController@login');
// Public search medication to NIH
Route::get('drugs/search', [DrugsApiController::class, 'search']);

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Users
    Route::apiResource('users', 'UsersApiController');
    // Drugs
    Route::apiResource('drugs', 'DrugsApiController');
    // Custom Drugs
    Route::post('medication/save', [DrugsApiController::class, 'saveUserMedication']);
    Route::delete('medication/delete', [DrugsApiController::class, 'deleteUserMedication']);
    Route::get('medication/all', [DrugsApiController::class, 'getUserMedication']);
    Route::get('medication/details', [DrugsApiController::class, 'getMedicationDetails']);
});
