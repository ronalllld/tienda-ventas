<?php

use Illuminate\Support\Facades\Route;

Route::get('/{cualquier?}', function () {
    return view('app');
})->where('cualquier', '^(?!api|sanctum|storage).*$');
