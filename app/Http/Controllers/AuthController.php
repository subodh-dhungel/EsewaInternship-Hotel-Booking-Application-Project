<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    //registration form dekhauna ko lagi
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {

        // 1. Aaune data lai validate garne
        $credentials = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:30'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed']
        ]);

        // 2. database ma naya user create garne
        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);

        // 3. newly created user lai role assign garera logged in garne
        $customerRole = Role::where('name', 'customer')->first();
        $user->roles()->attach($customerRole);
        Auth::login($user);

        // 4. User lai homepage ma redirect garne login pachi
        return redirect(route('hotels.featured'));
    }

    //login form dekhauna ko lagi
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // login credentials lai validate garne

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // User le remember ma click gareko case ma
        $remember = $request->boolean('remember');
        if(Auth::attempt($credentials, $remember)){

            // login garepachi session regenerate garne
            $request->session()->regenerate();

            // redirect users to the homepage
            return redirect()->intended(route('hotels.featured'));

        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // log the user out
        Auth::logout();
        //invalidate the current session
        $request->session()->invalidate();
        //regenerate the CSRF token
        $request->session()->regenerateToken();
        //redirect to the login page
        return redirect()->route('show.login');
    }
}
