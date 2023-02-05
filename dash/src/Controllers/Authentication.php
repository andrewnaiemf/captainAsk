<?php
namespace Dash\Controllers;
use App\Http\Controllers\Controller;

class Authentication extends Controller {

	public function index() {
		return view('dash::login');
	}

	public function loggedin() {
		$this->validate(request(), [
				'email'    => 'required|email',
				'password' => 'required',
			], [], [
				'email'    => __('dash::dash.email'),
				'password' => __('dash::dash.password'),
			]);
		$remember = !empty(request('remember_me'))?true:false;
		if (auth()->guard('dash')->attempt(['email' => request('email'), 'password' => request('password'), 'account_type' => 'admin'], $remember)) {
			return redirect(app('dash')['DASHBOARD_PATH'].'/dashboard');
		} else {
			session()->flash('error', __('dash::dash.failed_login_msg'));
			return back()->withInput();
		}
	}

	public function logout() {
		if (auth()->guard('dash')->logout()) {
			return redirect(app('dash')['DASHBOARD_PATH'].'/login');
		} else {
			return redirect(app('dash')['DASHBOARD_PATH'].'/dashboard');
		}
	}
}