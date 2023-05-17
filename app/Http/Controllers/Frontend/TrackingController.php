<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use GoogleMaps\GoogleMaps;
use GuzzleHttp\Client;

class TrackingController extends Controller
{
    public function show($firebaseId)
    {

        // Get the Firebase Realtime Database reference
        $database = app('firebase.firestore')->database();

        // Get the trip data from Firebase Realtime Database
        $tripData = $database->collection('trips')->document($firebaseId)->snapshot()->data();

        return view('Frontend.Trip.tracking', compact('tripData'));
    }
}
