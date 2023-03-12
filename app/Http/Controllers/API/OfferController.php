<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Offer;
use App\Models\Trip;


class OfferController extends Controller
{
    use GeneralTrait;

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

        if( $offer && in_array($offer->accepted , [null , 0]) ){

            $offer->update(['amount' => $request->amount , 'accepted' => null ]);
            $message = 'api.Offer_updated_successfully';

        }else if( $offer && $offer->trip_id != $request->trip_id  ){
            Offer::Create([
                'trip_id' => $request->trip_id ,
                'captain_id' => auth()->user()->id,
                'amount' => $request->amount
            ]);
            $message = 'api.Offer_created_successfully';
        }else{
            return $this->returnError( trans("api.InvalidRequest"));
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
