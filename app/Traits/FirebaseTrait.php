<?php

namespace App\Traits;

use App\Mappers\FirestoreMapper;

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
        $docRef->set( $data );
        return $docId;
    }

    public function updateTrip($trip, $updated_data){

        $data = FirestoreMapper::toFirestore($trip);
        $docRef =  $this->ref->document($trip->firebaseId);

        $updateData = [];

        foreach ($updated_data as $key => $value) {
            $updateData[] = ['path' => $key, 'operator' => '=', 'value' =>  $value];
        }
        $res = $docRef->update($updateData);
    }

}
