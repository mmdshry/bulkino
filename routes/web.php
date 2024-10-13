<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/only/i/have/access/to/this/url', function (Request $request) {
    $token = User::first()->createToken('api');

    dd(['token' => $token->plainTextToken]);
});