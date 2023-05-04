<?php

namespace App\Traits;

use GoogleMaps\GoogleMaps;
use GuzzleHttp\Client;

trait GeolocationTrait
{
    public function getDistance($origin, $destination)
    {
        $googleMaps = new GoogleMaps(new Client());

        $directions = $googleMaps->load('directions')
            ->setParam([
                'origin'          => $origin,
                'destination'     => $destination,
                'units' => 'metric',
                'mode' => 'driving',
                'language' => 'en'
            ])->get();
        $directions = json_decode($directions, true);

        if ($directions['status'] === 'OK') {
            $distanceInMeters = $directions['routes'][0]['legs'][0]['distance']['value'];
            $distanceInKilometers = $distanceInMeters / 1000;
            $duratioPerMinutes = $directions['routes'][0]['legs'][0]['duration']['value'] ;
            $data = [
                'distanceInKilometers'=>$distanceInKilometers ,
                'duration' =>$duratioPerMinutes
            ];
            return $data ;

        } else {
            return ;
        }
    }
}
