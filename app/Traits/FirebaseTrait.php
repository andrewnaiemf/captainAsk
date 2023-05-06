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


    public function offerFirebase($firebaseId = null ,$trip = null ,$offerData = null ,$captain = null ,$location = null){

        $docRef = $this->ref->document($trip->firebaseId);
        if($firebaseId){//update offer

            $subCollection = $this->ref->document($trip->firebaseId)->collection('captains')->document($firebaseId);
            if (isset($offerData['amount'])){// add amount in step 2 to create trip
                $data = [
                    'amount' => $offerData['amount'],
                    'arrival_time' => ceil($location['duration'] / 60),//per minutes
                    'distance' =>ceil($location['distanceInKilometers']) ,
                    'status' => 'Pending',
                ];
            }elseif($offerData['status']){

                $status = $offerData['status'] == 'Decline' ? 'Declined' : 'Accepted';
                $data =['status' => $status];
                $updateData=[];

                foreach ($data as $key => $value) {
                    $updateData[] = ['path' => $key, 'operator' => '=', 'value' => $value];
                }

                $subCollection->update($updateData);

                 if($offerData['status'] == 'Accept'){//update the trip status with accepted , update the offer status with accept , and delete all other offers

                     $query = $docRef->collection('captains')->where('status', '!=','Accepted');
                     $docs = $query->documents()->rows();
                     foreach ($docs as $doc) {
                         $doc->reference()->delete();
                     }

                     $this->updateTrip($trip ,['status' => 'Accepted']);
                }
            }

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
                'distance' =>ceil($location['distanceInKilometers']),
                'uuId' => $captain->uuid,
                'phne' => $captain->phone,
                'lat' => $offerData['lat'],
                'lng' => $offerData['lng']
            ]);

            $newDocId = $newDocRef->id();

            return $newDocId;

        }

    }

}
