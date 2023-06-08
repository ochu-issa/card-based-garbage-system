<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Card;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthAppController extends Controller
{
    use HttpResponses;

    //sending Register user data
    public function RegisterUser(RegisterRequest $request)
    {

        $request->Validated($request->all());

        $userData = [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'password' => Hash::make('password'),
        ];

        $user = User::create($userData);
        $token = $user->createToken('Api token of ' . $request->f_name)->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token,
        ]);
    }

    //sending Login user data
    public function LoginUser(LoginRequest $request)
    {

        $request->validated($request->all());

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            return $this->error('', 'Invalid Crediantials', 401);
        }

        $user = User::whereemail($request->email)->first();
        $token = $user->createToken('Api Token for'. $user->f_name)->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token,
        ]);
    }

    //logout function
    public function LogoutUser()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success([
            'message' => 'You have successfully logout.'
        ]);
    }

    //

    
}
