<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\GeneralTrait;
use App\Models\User;
use App\Models\CaptainDetails;
use Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use GeneralTrait;

     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }


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

        $user=User::create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,

        ]);


        if ( $request->earn_area ) {
            $user->update(['account_type'=>'captain' ]);

            $captainDetail = CaptainDetails::create([
                'earn_area' => $request->earn_area,
                'user_id' => $user->id
            ]);
            $user['captainDetail'] =  $captainDetail ;
        }

        $credentials = $request->only(['phone','password']) ;

        $token= JWTAuth::attempt($credentials);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
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

            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = auth()->user();

        return $this->respondWithToken($token ,$user );
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
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
        return $this->respondWithToken(auth()->refresh());
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
        return response()->json([
            'user'=>$user,
            'access_token' => $token,
            // 'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
