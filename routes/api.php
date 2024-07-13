<?php

// Auth routes
Route::post('register', 'Api\\AuthController@register');
Route::post('login', 'Api\\AuthController@login');