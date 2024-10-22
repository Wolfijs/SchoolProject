<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Retrieve the login input and determine if it's an email or name
        $login = $request->input('login');
        $password = $request->input('password');

        // Prepare credentials array
        $credentials = [];
        
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Login using email
            $credentials['email'] = $login;
        } else {
            // Login using name
            $credentials['name'] = $login;
        }
        
        $credentials['password'] = $password;

        // Attempt to authenticate using email or name
        if (Auth::attempt($credentials)) {
            // Authentication passed
            return redirect()->intended('/');
        }

        // Authentication failed
        return redirect()->back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->withInput();
    }
}
