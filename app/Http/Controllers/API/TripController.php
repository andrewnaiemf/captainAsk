<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Captain;
use App\Models\CaptainService;
use App\Traits\FirebaseTrait;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Traits\GeolocationTrait;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Trip;
use App\Models\Rating;
use Illuminate\Support\Facades\Validator;
use App\Notifications\PushNotification;

class TripController extends Controller
{
    use GeneralTrait;
    use FirebaseTrait;
    use GeolocationTrait;

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
            ->orderBy('id', 'desc')
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
            'notes' => 'required_if:service_id,3',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
        $request['customer_id'] = auth()->user()->id;

        $trip = Trip::where(['customer_id' => auth()->user()->id])
        ->whereIn('status',['Accepted' ,'Pending'])->first();

        if($trip){
            return $this->returnError( trans("api.cannotstarttripnow"));
        }

        $user_wallet = '0';
        $user_details = auth()->user()->customerDetail()->first();
        if(isset($user_details)){
            $user_wallet =  $user_details->wallet;
        }
        $origin = $request->start_lat .', ' .$request->start_lng;
        $destination = $request->end_lat .', ' .$request->end_lng;
        $location = $this->getDistance($origin, $destination);
        $distance = $location['distanceInKilometers'];

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
            $request['distance'] = $distance;
            $request['notes'] = $request->notes ?? null;
            $request['min_cost'] = $min_cost;
            $trip = Trip::create($request->all());
            $data['trip'] = $trip;
            $docId = $this->addNewTrip($trip);//pass the trip to firebase trait and return its id

            $trip->update(['firebaseId' => $docId]);

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
                $trips = $captain->trips()
                ->with(['customer','captain'])
                ->whereIn('status' , ['Accepted','Started'])
                ->orderBy('id', 'desc')
                ->simplePaginate($perPage);
            }else if ( $status == 'old' ){
                $trips = $captain->trips()
                ->whereNotIn('status', ['Accepted', 'Pending'])
                ->with(['customer','captain'])
                ->orderBy('id', 'desc')
                ->simplePaginate($perPage);
            }
        }else{
            $customer = User::find(auth()->user()->id);

            if ( $status == 'new' ) {
                $trips = $customer->trips()
                ->with(['customer','captain'])->with('offers' , function ($q){
                    $q->where('accepted' ,1);
                })
                ->whereIn('status' , ['Accepted' ,'Pending' ,'Started'])
                ->orderBy('id', 'desc')
                ->simplePaginate($perPage);
            }else if ( $status == 'old' ){
                $trips = $customer->trips()
                ->whereNotIn('status', ['Accepted', 'Pending'])
                ->with(['customer','captain'])
                ->orderBy('id', 'desc')
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
                'status' => 'required|in:Accepted,Rejected,Finished,Started',
            ]);

            if ($validator->fails()) {
                return $this->returnValidationError(401,$validator->errors()->all());
            }


            $trip = Trip::find($id);
            $captain = Captain::find(auth()->user()->id) ;

            if ( $trip &&  !in_array($trip->status , ['Finished','Rejected'])  && $captain->account_type == 'captain') {

                if ( $trip->captain_id == $captain->id ) {

                    if ( $request->status == 'Finished' &&  $trip->status == 'Started' ) {


                        if ( $trip->paymentMethod == 'card') {
                            $captain_wallet =  $trip->cost + $captain->captainDetail->wallet ;
                            $captain->captainDetail()->update(['wallet' => $captain_wallet]);
                        }

                        $message = trans("api.tripFinishedSuccessfully") ;

                    }else if ( $request->status == 'Rejected'  &&  $trip->status == 'Accepted' ) {

                        $message = trans("api.tripRejectedSuccessfully") ;

                    }else if ( $request->status == 'Started'  &&  $trip->status == 'Accepted' ) {

                        $message = trans("api.tripStartedSuccessfully") ;

                    }
                    else{
                        $message = trans("api.InvalidRequest") ;
                    }

                    if($trip->status == 'Accepted' &&   in_array($request->status , ['Finished','Rejected','Started'])){

                        $trip->update(['status' => $request->status]);
                        $this->updateTrip($trip , $request->all());

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
                'paymentMethod' => 'required|in:cash,card',
                'cost' => 'required|numeric|between:0,999999',
                'status' => 'nullable|in:canceled'
            ]);

            if ($validator->fails()) {
                return $this->returnValidationError(401,$validator->errors()->all());
            }


            $trip = Trip::find($id);

            if($request->cost <  $trip->min_cost){
                return $this->returnError( trans("api.Costshouldover") . $trip->min_cost);
            }

            $trip->update([
                'paymentMethod' => $request->paymentMethod,
                'cost' => $request->cost,
                'status' => $request->status ?? $trip->status
            ]);

            $request['status'] = $request->status ?? 'Pending';
            $this->updateTrip($trip , $request->all());


            if ($request->status == 'Pending') {

                $captains_deviceTokens = Captain::whereHas('captainDetail', function ($query) use ($trip){
                    $query->where(['service_id'=> $trip->service_id , 'is_busy' => false]);
                })->where('status','Accepted')->pluck('device_token');

                $message = "There is a new trip";
                $notifyDevices = PushNotification::send($captains_deviceTokens , $message);
            }


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


    public function notify($id){

        $trip = Trip::find($id);

        if ($trip && $trip->status == 'Accepted') {
            $customer = User::find($trip->customer_id);
            if (!$trip->user_notified) {
                $result = PushNotification::send([$customer->device_token] ,'the captain will arrive after 5 mins');
                $trip->update(['user_notified' => true]);
                $this->updateTrip($trip ,['arrive_soon' => true]);
                return $this->returnSuccessMessage( trans("api.customerNotifiedSuccessfully") );
            }
        }
        return $this->returnError( trans("api.InvalidRequest"));

    }
}
