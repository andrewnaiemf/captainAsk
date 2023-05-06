<?php

namespace App\Mappers;


use App\Models\Trip;

class FirestoreMapper
{
    public static function toFirestore(Trip $trip): array
    {
        return [
            'trip_id' =>$trip->id,
            'service_id' => $trip->service_id,
            'start_address' => $trip->start_address,
            'start_lat' => $trip->start_lat,
            'start_lng' => $trip->start_lng,
            'end_address' => $trip->end_address,
            'end_lat' => $trip->end_lat,
            'end_lng' => $trip->end_lng,
            'paymentMethod' => $trip->paymentMethod,
            'cost' => $trip->cost,
            'status' => $trip->status,
            'customer_picture' => auth()->user()->account_type == 'user' ? auth()->user()->customer_profile : '',
            'name' => auth()->user()->fullname,
            'rate' => auth()->user()->rating
        ];
    }
}
