<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Traits\GeneralTrait;

class AdvertisementController extends Controller
{
    use GeneralTrait;

    public function index(){

        $advertisements = Advertisement::all();

        return $this->returnData([ 'advertisements' => $advertisements ]);


    }
}
