<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Login View
    public function LoginPage()
    {
        return view('auth.login');
    }

    //check auth validation
    public function AuthValidation(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;
        if(Auth::attempt(['email' => $email, 'password' => $password])){
            return redirect()->route('dashboard')->with('success', '[Login Successfully]');
        }else{
            return redirect()->back()->with('error', '[Invalid Crediantials]');
        }
    }

    //function to logout
    public function Logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Welcome back!');
    }

}
