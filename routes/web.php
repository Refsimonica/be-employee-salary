<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return redirect(env('APP_FE_URL'));
});
