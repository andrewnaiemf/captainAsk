<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Trip;
use App\Notifications\PushNotification;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
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
            'rate' => 'required|numeric|between:0,5',
            'feedback' => 'nullable',
            'trip_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }

        $trip = Trip::find($request->trip_id);
        $user = auth()->user() ;
        $user_id = $user->account_type == 'captain' ? $trip->customer_id : $trip->captain_id ;
        $rate = Rating::where(['trip_id' => $request->trip_id , 'user_id' =>  $user_id])->first();


        if( ! isset($rate)) {

            Rating::create([
                'user_id' => $user_id,
                'trip_id' => $request->trip_id,
                'rating' => $request->rate,
                'feedback' => $request->feedback,
                'rated_by_customer_id' => $user->id
            ]);

            PushNotification::send([$devices_token ?? $user->device_token] ,'rating');

            return $this->returnSuccessMessage( trans("api.ratingSetSuccessfully") );

        }

        return $this->returnError( trans("api.InvalidRequest"));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request ,$id)
    {
        $perPage = $request->header('per_page', 10);
        $rates = Rating::with(['ratedByCustomer'])
        ->where('user_id' ,$id)
        ->orderBy('id', 'desc')
        ->simplePaginate($perPage);

        return $this->returnData($rates);
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
