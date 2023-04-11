<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Captain;
use App\Models\CaptainService;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Trip;
use App\Models\Rating;
use Illuminate\Support\Facades\Validator;
use GoogleMaps\GoogleMaps;
use GuzzleHttp\Client;

class TripController extends Controller
{
    use GeneralTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->header('per_page', 10);
        $user_type = auth()->user()->account_type ;
        if ( $user_type == 'captain') {

            $trips = Trip::where('status', 'Pending')
            ->with(['customer','captain'])
            ->simplePaginate($perPage);

            return $this->returnData ( ['trips' => $trips] );
        }
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
            'service_id' => 'required|in:1,2,3,4,5',
            'start_address' => 'required',
            'start_lat' => 'required',
            'start_lng' => 'required',
            'end_address' => 'required',
            'end_lat' => 'required',
            'end_lng' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
        $request['customer_id'] = auth()->user()->id;

        $user_wallet = '0';
        $user_details = auth()->user()->customerDetail()->first();
        if(isset($user_details)){
            $user_wallet =  $user_details->wallet;
        }
        $origin = $request->start_lat .', ' .$request->start_lng;
        $destination = $request->end_lat .', ' .$request->end_lng;
        $distance = $this->getDistance($origin, $destination);

        if ($distance){
            $data = [];
            $cost = ceil($distance * 10) ; // 10 SAR per 1 kilo
            $min_cost = $cost - 5 ;

            $data['wallet'] = $user_wallet;
            $data['can_pay'] = false;
            $data['min_cost'] = $min_cost;
            $data['cost'] = $cost;

            if ($user_wallet > $min_cost)
            {
                $data['can_pay'] = true;
            }
            $trip = Trip::create($request->all());
            $data['trip'] = $trip;

           return $this->returnData($data);
        }else{
            return $this->returnError( trans("api.InvalidRequest"));
        }

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

    public function tripBystatus(Request $request , $status)
    {
        $perPage = $request->header('per_page', 10);
        $user_type = auth()->user()->account_type ;
        if ( $user_type == 'captain') {

            $captain = Captain::find(auth()->user()->id);

            if ( $status == 'new' ) {
                $trips = $captain->trips()->with(['customer','captain'])->where('status' , 'Accepted')->simplePaginate($perPage);
            }else if ( $status == 'old' ){
                $trips = $captain->trips()
                ->with(['customer','captain'])
                ->where('status','!=', 'Accepted')
                ->simplePaginate($perPage);
            }
        }else{
            $customer = User::find(auth()->user()->id);

            if ( $status == 'new' ) {
                $trips = $customer->trips()->with(['customer','captain'])->where('status' , 'Accepted')->simplePaginate($perPage);
            }else if ( $status == 'old' ){
                $trips = $customer->trips()
                ->with(['customer','captain'])
                ->where('status','!=', 'Accepted')
                ->simplePaginate($perPage);
            }
        }
        return $this->returnData ( ['trips' => $trips] );
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

        if (auth()->user()->account_type == 'captain') {
            $validator=Validator::make($request->all(), [
                'status' => 'required|in:Accepted,Rejected,Finished',
            ]);

            if ($validator->fails()) {
                return $this->returnValidationError(401,$validator->errors()->all());
            }


            $trip = Trip::find($id);
            $captain = Captain::find(auth()->user()->id) ;

            if ( $trip &&  !in_array($trip->status , ['Finished','Rejected'])  && $captain->account_type == 'captain') {

                if ( $trip->captain_id == $captain->id ) {

                    if ( $request->status == 'Finished' &&  $trip->status == 'Accepted' ) {

                        $trip->update(['status' => $request->status]);

                        if ( $trip->paymentMethod == 'card') {
                            $captain_wallet =  $trip->cost + $captain->captainDetail->wallet ;
                            $captain->captainDetail()->update(['wallet' => $captain_wallet]);
                        }
                        $message = trans("api.tripFinishedSuccessfully") ;

                    }else if ( $request->status == 'Rejected'  &&  $trip->status == 'Accepted' ) {

                        $trip->update(['status' => $request->status]);
                        $message = trans("api.tripRejectedSuccessfully") ;

                    }else{
                        $message = trans("api.InvalidRequest") ;
                    }

                }

                if (  $request->status == 'Accepted'  &&  $trip->status == 'Pending' ) {

                    $trip->update(['status' => $request->status , 'captain_id' => $captain->id]);
                    $message = trans("api.tripAcceptedSuccessfully") ;

                }

                return $this->returnSuccessMessage( $message  );

            }

            return $this->returnError( trans("api.InvalidRequest"));
        }else{

            $validator=Validator::make($request->all(), [
                'paymentMethod' => 'required',
                'cost' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->returnValidationError(401,$validator->errors()->all());
            }


            $trip = Trip::find($id);
            $trip->update([
                'paymentMethod' => $request->paymentMethod,
                'cost' => $request->cost
            ]);
            $trip['firebaseId'] = strtotime("now");
            return $this->returnData($trip);

        }

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

            return $distanceInKilometers ;

        } else {
          return ;
        }
    }
}
