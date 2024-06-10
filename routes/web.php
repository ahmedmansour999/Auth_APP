<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('/chat', 'App\Http\Controllers\UserController@index')->name('main_chat');

    Route::get('/chat/{id}', 'App\Http\Controllers\MessageController@OneMessage')->name('user_chat');
    Route::post('/sendMessage', 'App\Http\Controllers\MessageController@SendMessage')->name('SendMessage');


    Route::get('/openChat', 'App\Http\Controllers\ChatController@openChat')->name('openChat');
    Route::post('/chat/new', 'App\Http\Controllers\ChatController@newChat')->name('chat');
});
