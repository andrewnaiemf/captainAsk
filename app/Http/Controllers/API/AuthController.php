<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Captain;
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
        $validator=Validator::make($request->all(), [
                    'f_name' => 'required',
                    'l_name' => 'required',
                    'phone' => 'required|numeric|unique:users',
                    'password' => 'required|string|min:6',
                    'earn_area' => 'required|string',
                 ]);
        if ($validator->fails()) {
           return response()->json(['error'=>$validator->errors()], 401);
        }

        if ( $request->earn_area ) {

            $user=Captain::create([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,

            ]);

            $user->update(['account_type'=>'captain' ]);

            $captainDetail = CaptainDetails::create([
                'earn_area' => $request->earn_area,
                'user_id' => $user->id,
                'service_id' => 0
            ]);

            $user->load('captainDetail');
            $user = $user->toArray();
        }else{

            $user=User::create([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,

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
        $validatedData = $request->validate([
            'phone' => 'required|numeric',
            'password' => 'required|string|min:6',
        ]);

        $credentials = request(['phone', 'password']);

        if (! $token = JWTAuth::attempt($credentials)) {
            return $this->unauthorized();
        }

        $user = auth()->user();

        if (auth()->user()->account_type == 'captain') {
            $user = Captain::find($user->id);
            $user->load('captainDetail');
        }

        return $this->respondWithToken($token ,$user );
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->returnData(['user', auth()->user()]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
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

}
