<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Captain;
use App\Models\User;
use App\Models\CaptainDetails;
use App\Models\CustomerDetail;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    use GeneralTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallet = '0';
        if(auth()->user()->account_type == 'captain'){
            $user = Captain::find( auth()->user()->id );
            if($user->captainDetail()->first()){
                $wallet = $user->captainDetail()->first()->wallet ;
            }
        }else{
            $user = User::find( auth()->user()->id );
            if( $user->customerDetail()->first()){
                $wallet = $user->customerDetail()->first()->wallet ;
            }
        }

        $cards = $user->cards()->get();

        return $this->returnData([ 'wallet' => $wallet , 'cards' => $cards ]);


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

    public function transfer(Request $request){
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|exists:users,phone',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }

        $loggedCustomerDetails = CustomerDetail::where(['user_id'=>auth()->user()->id])->first();

        if($loggedCustomerDetails->wallet <  $request->amount){
           return $this->returnError( trans('api.You_do_not_have_enough_points_balance_in_your_wallet') );
        }

        $receiver = User::where('phone' , $request->phone)->first();
        if($receiver->account_type == 'captain'){
            $captainDetails = CaptainDetails::where(['user_id'=>$receiver->id])->first();
            $totalAmount =  $captainDetails->wallet + $request->amount;
            $captainDetails->update(['wallet' => $totalAmount]);
        }else{
            $customerDetails = CustomerDetail::where(['user_id'=>$receiver->id])->first();
            $totalAmount =  $customerDetails->wallet + $request->amount;
            $customerDetails->update(['wallet' => $totalAmount]);
        }

        $loggedCustomerTotalAmount =  $loggedCustomerDetails->wallet - $request->amount ;
        $loggedCustomerDetails->update(['wallet' => $loggedCustomerTotalAmount]);

        return $this->returnSuccessMessage( trans("api.transferCompletedSuccessfully")  );

    }
}
