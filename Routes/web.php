<?php

use Illuminate\Support\Facades\Route;

Route::get('pos', function () {
    return view('sales-purchases::pos');
});
