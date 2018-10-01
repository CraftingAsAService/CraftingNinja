<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;
use Socialite;

use App\Models\User;

class LoginController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}

	/**
	 * Redirect the user to the Google authentication page.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function redirectToProvider($provider)
	{
		if ( ! session()->has('url.intended'))
			session()->put('url.intended', url()->previous());

		return Socialite::driver($provider)->redirect();
	}

	/**
	 * Obtain the user information from Google.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function handleProviderCallback($provider)
	{
		$user = Socialite::driver($provider)->user();
		$authUser = $this->findOrCreateUser($user, $provider);
		Auth::login($authUser, true);
		return redirect()->intended('/');
	}

	/**
	 * If a user has registered before using social auth, return the user
	 * else, create a new user object.
	 * @param  $user Socialite user object
	 * @param $provider Social auth provider
	 * @return  User
	 */
	public function findOrCreateUser($user, $provider)
	{
		$authUser = User::where('provider_id', $user->id)->first();

		if ($authUser)
			return $authUser;

		return User::create([
			'name'     => $user->nickname,
			'email'    => $user->email,
			'provider' => $provider,
			'provider_id' => $user->id,
			'avatar' => $user->avatar
		]);
	}

}
