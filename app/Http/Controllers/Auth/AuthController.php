<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:8'
        ]);

        $rememberMe = $request->remember ? true : false;

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $rememberMe)) {

                notify()->success('Welcome to Power Cosmo  ⚡️', 'Login Successfully');
                return redirect()->route('home');

        }

        notify()->error('Invalid credentials');
        return redirect()->route('login')->withInput($request->only('email', 'remember'));
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }
}
