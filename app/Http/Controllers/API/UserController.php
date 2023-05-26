<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Captain;
use App\Models\CaptainCarDetail;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\CustomerDetail;
use App\Models\CaptainDocument;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use GeneralTrait;

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $validation =  $this->validateUserData( $request );

        if ( $validation) {
            return $validation;
        }

        $user = User::find(auth()->user()->id);
        $user->update([
            'online' => intval(filter_var($request['is_online'], FILTER_VALIDATE_BOOLEAN) ?? $user->online),
            'verified' => intval(filter_var($request['verified'], FILTER_VALIDATE_BOOLEAN) ?? $user->verified),
        ] + $request->except(['online', 'verified']));

        if ( $user->account_type == 'captain' ){

            $user = Captain::find($user->id);
            if( $request->service_id ){
                $user->captainDetail->Update([
                    'service_id' => $request->service_id
                ]);
            }


            $documents = $request->file('documents', []);
            $this->captainDocuments(  $documents, $user );

            $cardetails = $request['car-plate'];

            if ( isset($cardetails) ) {
                $this->captainCardetails(  $cardetails, $user );
            }

        }else{

            if( $request->file('profile') ){
                $document = $request->file('profile');
                $this->customerDocuments(  $document, $user );
            }
        }

        return $this->returnSuccessMessage( trans("api.user'sdataUpdatedSuccessfully") );
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


    public function validateUserData ( $request ) {

        $validator=Validator::make($request->all(), [
            'online' => 'boolean',
            'verified' => 'in:true,false,0,1',
            'phone' => [
                Rule::unique('users')->ignore(auth()->user()->id)
            ],
            'f_name' => 'string|max:255',
            'l_name' => 'string|max:255',
            'service_id' => 'in:1,2,3,4,5',
            'documents.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'car-plate.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
    }

    public function captainDocuments( $documents, $user )
    {
        $path = 'captainDocument/' .$user->id. '/';

        foreach ($documents as $type=>$doc) {

            $document = $user->documents()
            ->where('type' , $type )
            // ->where('path' ,'!=', '' )
            ->first();

            if ( !empty($document->path) ) {
                $segments = explode('/', $document->path);
                $imageName = $segments[2];
                $doc->storeAs($path,$imageName);

                if( in_array($document->status , ['Accepted' , 'Rejected'])  ){
                    $document->update([
                        'status' => 'Pending'
                    ]);
                }
            }elseif ( isset($document) && empty($document->path)) {

                $imageName = $doc->hashName();
                $doc->storeAs($path,$imageName);
                $full_path = $path.$imageName;
                $document->update(['path'=> $full_path]);

                if( $document->status == 'New'){
                    $document->update([
                        'status' => 'Pending'
                    ]);
                }


            } else {
                $imageName = $doc->hashName();
                $doc->storeAs($path,$imageName);
                $full_path = $path.$imageName;
                // $document = $user->documents()->where('type' , $type)->first();

                CaptainDocument::create([
                    'captain_id' => $user->id,
                    'name' => $type,
                    'type' => $type,
                    'path' => $full_path,
                    'status' => 'Pending'
                ]);
            }
        }
    }

    public function captainCardetails( $requestCarDetails, $user )
    {
        $carDetails = $user->captainCarDetail;

        if ( $carDetails ) {

            $carDetails->update($requestCarDetails);
        } else {
            $requestCarDetails['captain_id'] = $user->id;
            CaptainCarDetail::create($requestCarDetails);
        }

    }

    public function customerDocuments( $document, $user )
    {
        $path = 'customer/' .$user->id. '/';


        $user_document = $user->customerDetail()->first();

        if ( $user_document ) {
            $segments = explode('/', $user_document->profile_picture);
            $imageName = $segments[2];
            $document->storeAs($path,$imageName);
        } else {
            $imageName = $document->hashName();
            $document->storeAs($path,$imageName);
            $full_path = $path.$imageName;
            $document = $user->customerDetail()->first();

            CustomerDetail::create([
                'user_id' => $user->id,
                'profile_picture' => $full_path,
            ]);
        }

    }
}
