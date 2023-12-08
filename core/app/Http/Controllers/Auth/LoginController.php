<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function customLoginForm()
    {
        if (auth()->check()) {
            return redirect()->intended('dashboard');
        }

        return to_route('login');
    }

    public function customLogin(Request $request)
    {
        $validated = $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:8'
        ]);

        if (auth()->attempt(['email' => e(strip_tags($validated['email'])), 'password' => e(strip_tags($validated['password']))])) {
            return redirect()->intended('dashboard');
        }

        return back()->withInput($request->only('email', 'remember'));
    }
}
