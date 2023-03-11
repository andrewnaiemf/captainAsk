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
                $trip = $captain->trips()->with(['customer','captain'])->where('status' , 'Pending')->simplePaginate($perPage);
                return $this->returnData ( ['trip' => $trip] );
            }else if ( $status == 'old' ){
                $trips = $captain->trips()
                ->with(['customer','captain'])
                ->whereNotIn('status', ['Pending','New'])
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
