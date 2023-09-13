<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Captain;
use App\Models\CaptainDocument;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\GeneralTrait;
use App\Models\User;
use App\Models\CaptainDetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    use GeneralTrait;


    public function register(Request $request)
    {
        $rules = [
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required|numeric|unique:users',
            'password' => 'required|string|min:6',
        ];

        if ($request->earn_area) {
            $rules['earn_area'] = 'required|string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->returnValidationError(401, $validator->errors()->all());
        }

        if ( $request->earn_area ) {

            $user=Captain::create([
                'uuid' => strtotime("now"),
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'status' => 'New',
                'online' => 1,
                'verified' => 0,
                "device_token"=> $request->device_token,
                'locale' => $request->header('locale', 'ar')
            ]);

            $user->update(['account_type'=>'captain' ]);

            $captainDetail = CaptainDetails::create([
                'earn_area' => $request->earn_area,
                'user_id' => $user->id,
                'service_id' => 0
            ]);

            $documents = ['Profile','Car_form','Car_license_front','Car_license_back','Captain_license','Insurance_documentation','personal_id_front','personal_id_back'];
            foreach ( $documents as $document ) {
                CaptainDocument::create([
                    'captain_id' => $user->id,
                    'name' => $document,
                    'type' => $document,
                    'path' => $document == 'Profile' ? 'default/default.png'  : '',
                    'status' => 'New'
                ]);
            }

            $user->load('captainDetail');
            $user->load('documents');
            $user = $user->toArray();
        }else{

            $user=User::create([
                'uuid' => strtotime("now"),
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'online' => 1,
                'verified' => 0,
                'device_token' => $request->device_token,
                'locale' => $request->header('locale', 'ar')
            ]);

        }

        $credentials = $request->only(['phone','password']) ;

        $token= JWTAuth::attempt($credentials);
        if (!$token) {
            return $this->unauthorized();
        }
        // Mail::to($customer->email)->send(new SignupWelcome($customer,$lang));
      return  $this->respondWithToken($token,$user);


    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|exists:users,phone',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }

        $credentials = request(['phone', 'password']);

        if (! $token = JWTAuth::attempt($credentials)) {

            return $this->returnError( trans("api.Password_is_incorrect"));
        }

        $user = auth()->user();
        $user->update(['locale' => $request->header('locale', 'ar')]);

        $this->device_token($request->device_token);

        if (auth()->user()->account_type == 'captain' && $request->type == 'captain') {
            $user = Captain::find($user->id);
            $user->load('captainDetail');
            $user->load('captainCarDetail');
            $user->load('documents');
        }
        elseif(auth()->user()->account_type == 'user' && $request->type == 'user'){
            $trip = auth()->user()->trips()->whereIn('status', ['Accepted' ,'Pending','Started'])
            ->with('offers' , function ($q){
                $q->where('accepted' ,1);
            })
            ->first();
            $user['trip'] = $trip;
        }else{

            return $this->returnError( trans("api.cantloginas".$request->type));

        }

        return $this->respondWithToken($token ,$user );
    }

    public function validate_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|unique:users',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
        return $this->returnSuccessMessage( trans("api.validPhone") );

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user_type = auth()->user()->account_type;

        if ( $user_type == 'captain' ) {
            $user = Captain::find(auth()->user()->id);
            $user->load('captainDetail' );
            $user->load('captainCarDetail');
            $user->load('documents');

            $msg =$user->status == 'Pending' ? "api.dataIsPending" : "api.dataIsRejected";

            $areAllDocumentsNew = true;
            foreach ($user->documents as $document) {
                if ($document->status != 'New') {
                    $areAllDocumentsNew = false;
                    break;
                }
            }

            if (empty($user->captain_car_detail) && $areAllDocumentsNew) {
                $msg = "";
            }

            return $this->returnData(['user' => $user ] ,trans($msg));
        }else{
            $user = User::find(auth()->user()->id);
            return $this->returnData(['user' => $user ]);
        }

    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return $this->returnSuccessMessage( trans("api.logged_out_successfully") );
    }


    public function changePassword(Request $request)
    {
        $user = auth()->user();
        $currentPassword = $request->current_password;
        $newPassword = $request->new_password;
        $confirmedPassword = $request->confirmed_password;

        if (!Hash::check($currentPassword, $user->password)) {
            return $this->returnError(trans('api.The_current_password_is_incorrect'));
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password|same:confirmed_password',
            'confirmed_password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);
        return $this->returnSuccessMessage( trans("api.Password_updated_successfully") );
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $user=User::find(auth()->user()->id);
        return $this->respondWithToken( $user->refresh(), $user);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token,$user)
    {
        return $this->returnData(['user' => $user , 'access_token' => $token]);
    }


    private function device_token($device_token){

        $user = auth()->user();
        if(!isset($user->device_token)){
            $user->update(['device_token'=>json_encode($device_token)]);
        }else{
            $devices_token = $user->device_token;

            if(! in_array( $device_token , $devices_token) ){
                array_push($devices_token ,$device_token );
                $user->update(['device_token'=>json_encode( $devices_token)]);
            }
        }
    }

    public function reset(Request $request){

        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:users,phone',
            'password' => 'required|confirmed|string|min:6',
            'password_confirmation' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
        $user = User::where('phone',$request->phone)->first();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->returnSuccessMessage( trans("api.Password_updated_successfully") );
    }
}
