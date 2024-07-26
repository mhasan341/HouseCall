<?php

// Auth routes
use App\Http\Controllers\Api\V1\Admin\DrugsApiController;
use GuzzleHttp\Client;

Route::post('register', 'Api\\AuthController@register');
Route::post('login', 'Api\\AuthController@login');
// Public search medication to NIH
Route::get('drugs/search', [DrugsApiController::class, 'search'])->name('drugs.search');

Route::get('/test', function (){
   return response()->json([
      "success" => true
   ], 200);
});

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
