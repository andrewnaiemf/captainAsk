<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertisement;
class AdvertisementController extends Controller
{
    public function index(){

        $advertisements = Advertisement::all();
        return response()->json(["success" => true, "advertisements" => $advertisements]);

    }
}
