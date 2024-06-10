<?php

use Illuminate\Support\Facades\Route;







Route::middleware('notAuth')->group(function () {

    Route::post('register', 'App\Http\Controllers\AuthController@register')->name('register');
    Route::get('/register', 'App\Http\Controllers\AuthController@registerView')->name('registerView');

    Route::post('login', 'App\Http\Controllers\AuthController@login')->name('login');
    Route::get('login', 'App\Http\Controllers\AuthController@loginView')->name('loginView');

    Route::get('verify/{id}', 'App\Http\Controllers\AuthController@verifyView')->name('verifyView');
    Route::post('verify/{id}', 'App\Http\Controllers\AuthController@verify')->name('verify');


    Route::post('resendOtp/{id}', 'App\Http\Controllers\AuthController@resendOtp')->name('resendOtp');


    Route::get('resetPass', 'App\Http\Controllers\AuthController@resetPassEnterEmail')->name('password');
    Route::get('passwordOtp/{id}', 'App\Http\Controllers\AuthController@passwordOtp')->name('passwordOtp');
    Route::get('resetPasswordForm/{id}', 'App\Http\Controllers\AuthController@newPasswordForm')->name('resetPasswordForm');
    Route::post('resetPass', 'App\Http\Controllers\AuthController@resetPassword')->name('resetPassword');
    Route::post('verifyOtpPassword/{id}', 'App\Http\Controllers\AuthController@verifyOtpPassword')->name('verifyOtpPassword');
    Route::post('resetPasswordForm/{id}', 'App\Http\Controllers\AuthController@resetPasswordForm')->name('changePassword');

});

    Route::get('logout', 'App\Http\Controllers\AuthController@logout')->name('logout')->middleware('auth');
