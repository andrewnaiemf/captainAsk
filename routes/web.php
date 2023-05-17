<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\TrackingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tracking/{firebaseId}', [TrackingController::class,'show'])->name('tracking.show');

