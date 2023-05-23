<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Captain;
use App\Traits\FirebaseTrait;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\GeolocationTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Offer;
use App\Models\Trip;
use App\Models\User;
use App\Notifications\PushNotification;

class OfferController extends Controller
{
    use GeneralTrait;
    use FirebaseTrait;
    use GeolocationTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'trip_id' => 'integer|required|exists:trips,id',
            'amount' => 'numeric|required|unique:captain_cards,number',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }

        $trip = Trip::find($request->trip_id);

        if( $trip->status != 'Pending' ){
            return $this->returnError( trans("api.InvalidRequest"));
        }

        $offer = Offer::where([
            'trip_id' => $request->trip_id ,
            'captain_id' => auth()->user()->id
        ])
        ->first();

        $captain = Captain::find(auth()->user()->id);

        $origin = $request->lat .', ' .$request->lng;
        $destination = $trip->start_lat .', ' .$trip->start_lng;
        $location = $this->getDistance($origin, $destination);

        if( $offer && !is_null($offer->accepted)  ){

            $message = trans('api.Offer_updated_successfully');

        }elseif(! isset($offer)){

            $message = trans('api.Offer_created_successfully');

        }else{

            return $this->returnError( trans("api.InvalidRequest"));

        }
        $offer_firebase_id = $offer['firebaseId'] ?? '' ;
        $result = $this->offerFirebase($offer_firebase_id ,$trip , $request->all() ,$captain ,$location);

        if ($result != 'update'){

            $offer = Offer::Create([
                'trip_id' => $request->trip_id ,
                'captain_id' => auth()->user()->id,
                'amount' => $request->amount
            ]);
            $offer->update(['firebaseId' => $result]);

        }else{

            $offer->update(['amount' => $request->amount , 'accepted' => null ]);

        }

        $notification_data['trip'] = $trip ;
        $customer = User::find($trip->customer_id);
        $result = PushNotification::send([$customer->device_token] ,'new_offer' , $notification_data);

        return $this->returnSuccessMessage( $message  );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(), [
            'status' => 'required|in:Decline,Accept',
            'captain_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }

        $offer = Offer::where(['firebaseId' => $id ,'captain_id' =>$request->captain_id ])->first();

        if ($offer){

            $trip = Trip::find($offer->trip_id);
            $this->offerFirebase($id ,$trip ,['status' => $request->status,'captain_id' => $request->captain_id]);

            if ($request->status == 'Decline'){
                $status = 0;

            }else{
                $status = 1;
                $trip->update([
                    'status' => 'Accepted',
                    'captain_id' => $request->captain_id
                ]);

                $captain = User::find($request->captain_id);
                //notify the captain that his offer is accepted
                $result = PushNotification::send([$captain->device_token] ,'accepted_offer');
            }
            $offer->update(['accepted' => $status]);

            return $this->returnSuccessMessage(  trans('api.Offer_updated_successfully')  );
        }

        return $this->returnError( trans("api.InvalidRequest"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
