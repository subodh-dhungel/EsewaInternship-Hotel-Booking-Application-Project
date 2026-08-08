<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //registration form dekhauna ko lagi
    public function showRegister(){
        return view('auth.register');
    }

    public function register(Request $request){

        // 1. Aaune data lai validate garne
        $credentials = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:30'],
            'email'=> ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed']
        ]);

        // 2. database ma naya user create garne
        $user = User::create([
            'name'=>$credentials['name'],
            'email'=>$credentials['email'],
            'password'=>$credentials['password'],
            'role'=>'customer'
        ]);

        // 3. newly created user lai logged in garne
        Auth::login($user);

        // 4. User lai homepage ma redirect garne login pachi
        return redirect(route('hotels.featured'));
    }

    public function showLogin(){

    }

    public function login(Request $request){

    }

    public function logout(Request $request){
        
    }
}
