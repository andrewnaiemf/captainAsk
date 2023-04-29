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

    public function updateTrip($trip){

        $data = FirestoreMapper::toFirestore($trip);

        $docRef =  $this->ref->document($trip->firebaseId);
        $docRef->update($data, ['merge' => true]);

        return true;

    }

}
