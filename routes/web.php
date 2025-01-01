<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\EmailController;

Route::get('/emails', [EmailController::class, 'fetchEmails']);