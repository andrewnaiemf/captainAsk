<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\TrackingController;
use App\Http\Controllers\Frontend\TermsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tracking/{firebaseId}', [TrackingController::class,'show'])->name('tracking.show');

Route::get('/en/terms', [TermsController::class,'termsEn']);
Route::get('/ar/terms', [TermsController::class,'termsAr']);
