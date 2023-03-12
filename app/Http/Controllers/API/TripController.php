<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Captain;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Trip;
use App\Models\Rating;
use Illuminate\Support\Facades\Validator;

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
        //
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
                return $this->returnData ( ['trips' => $trips] );
            }else if ( $status == 'old' ){
                $trips = $captain->trips()
                ->with(['customer','captain'])
                ->where('status','!=', 'Accepted')
                ->simplePaginate($perPage);
                return $this->returnData ( ['trips' => $trips] );
            }
        }
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
