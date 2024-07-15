<?php

// Auth routes
Route::post('register', 'Api\\AuthController@register');
Route::post('login', 'Api\\AuthController@login');

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Users
    Route::apiResource('users', 'UsersApiController');
    // Drugs
    Route::post('drugs/media', 'DrugsApiController@storeMedia')->name('drugs.storeMedia');
    Route::apiResource('drugs', 'DrugsApiController');
});
