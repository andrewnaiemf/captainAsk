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
        $result = $this->offerFirebase($trip , $request->all() ,$captain ,$location ,$offer_firebase_id);

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
        //
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
