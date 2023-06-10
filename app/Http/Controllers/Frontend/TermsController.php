<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function termsEn(){
        return view('Frontend.terms_en');
    }

    public function termsAr(){
        return view('Frontend.terms_ar');
    }
}
