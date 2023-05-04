<?php

namespace App\Traits;

use App\Mappers\FirestoreMapper;
use Intervention\Image\Facades\Image;
use thiagoalessio\TesseractOCR\TesseractOCR;

trait FirebaseTrait
{

    protected $ref;

    public function __construct()
    {
        $this->ref = app('firebase.firestore')->database()->collection('trips');

    }

    public function addNewTrip($trip)
    {
        $data = FirestoreMapper::toFirestore($trip);

        $docRef = $this->ref->newDocument();
        $docId = $docRef->id();

        $docRef->set($data);
        return $docId;
    }

    public function updateTrip($trip, $updated_data)
    {

        $data = FirestoreMapper::toFirestore($trip);
        $docRef = $this->ref->document($trip->firebaseId);

        $updateData = [];

        foreach ($updated_data as $key => $value) {
            $updateData[] = ['path' => $key, 'operator' => '=', 'value' => $value];
        }
        $res = $docRef->update($updateData);
    }


    public function offerFirebase($trip ,$offerData ,$captain ,$location ,$firebaseId){

        $docRef = $this->ref->document($trip->firebaseId);
        if($firebaseId){//update offer

            $subCollection = $this->ref->document($trip->firebaseId)->collection('captains')->document($firebaseId);

            $data = [
                'amount' => $offerData['amount'],
                'amount' => $offerData['amount'],
                'arrival_time' => ceil($location['duration'] / 60),//per minutes
                'distance' =>ceil($location['distanceInKilometers']) ,
                'status' => 'Pending',
            ];
            $updateData=[];

            foreach ($data as $key => $value) {
                $updateData[] = ['path' => $key, 'operator' => '=', 'value' => $value];
            }

            $subCollection->update($updateData);
            return 'update';

        }else{//create offer

            $newDocRef = $docRef->collection('captains')->add([
                'id' => $captain->id,
                'status' => 'Pending',
                'name' => $captain->name,
                'image_url' => $captain->captain_profile,
                'rate' => $captain->rating,
                'car_number' => '111111',
                'amount' => $offerData['amount'],
                'arrival_time' => ceil($location['duration'] / 60),//per minutes
                'distance' =>ceil($location['distanceInKilometers']) ,
            ]);

            $newDocId = $newDocRef->id();

            return $newDocId;

        }

    }

}
