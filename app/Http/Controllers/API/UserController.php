<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Captain;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Validation\Rule;
use App\Models\User;
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
            'online' => boolval( $request['is_online'] ),
            'verified' => boolval( $request['verified'] ),
            'phone' => $request->phone ?? $user->phone,
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
        ]);

        if ( $user->account_type == 'captain' ){

            $user = Captain::find($user->id);
            if( $request->service_id ){
                $user->captainDetail->Update([
                    'service_id' => $request->service_id
                ]);
            }


            $documents = $request->file('documents', []);
            $this->userDocuments(  $documents, $user );
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
            'verified' => 'in:true,false',
            'phone' => [
                Rule::unique('users')->ignore(auth()->user()->id)
            ],
            'f_name' => 'string|max:255',
            'l_name' => 'string|max:255',
            'service_id' => 'in:1,2,3,4,5',
            'documents.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
    }

    public function userDocuments( $documents, $user )
    {
        $path = 'captainDocument/' .$user->id. '/';

        foreach ($documents as $type=>$doc) {

            $document = $user->documents()->where('type', $type)->first();
            if ( $document ) {

                $segments = explode('/', $document->path);
                $imageName = $segments[2];
                $doc->storeAs($path,$imageName);

            } else {

                $imageName = $doc->hashName();
                $doc->storeAs($path,$imageName);
                $full_path = $path.$imageName;
                $user->documents()->create([
                    'captain_id' => $user->id,
                    'name' => $type,
                    'type' => $type,
                    'path' =>$full_path
                ]);
            }
        }
    }

}
