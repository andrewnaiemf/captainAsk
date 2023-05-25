<?php
namespace Dash\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

class ResetPassword extends Controller {

    public function forgetpassword(Request $request)
    {
        if ( $request->isMethod('post')) {
            $validator=Validator::make($request->all(), [
                'email' => 'required|exists:users,email',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            $token = \Str::random(64);

            \DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
              ]);

              \Mail::send('dash::forgetpasstemplete', ['token' => $token], function($message) use($request){
                $message->to($request->email);
                $message->subject('Reset Password');
            });

            return back()->with('message', 'تم ارسال رسالة الى بريدك الالكتروني');
        }


		return view('dash::forgetpassword');
    }


    public function showResetForm($token = null)
    {
        if ($token) {
            return view('dash::resetpassword', ['token' => $token]);
        }

		return view('dash::forgetpassword');
    }


    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = \DB::table('password_resets')
                            ->where([
                              'email' => $request->email,
                              'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        \DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect()->route('captainAsk.login');
    }


}
