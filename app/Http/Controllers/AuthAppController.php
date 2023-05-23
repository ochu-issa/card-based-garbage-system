<?php

namespace App\Http\Controllers;

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
    public function LoginUser(Request $request)
    {
        $validate = validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => 'All fields are required!',
            ], 203);
        }

        $check_user = User::where('email', $request->email)->first();
        if (!$check_user || !Hash::check($request->password,  $check_user->password)) {
            return response([
                'message' => 'Invalid Credential',
            ], 202);
        }

        $token = $check_user->createToken('garbageApp')->plainTextToken;
        return response([
            'user' => $check_user,
            'token' => $token
        ], 200);
    }

    //check if number is exist?
    public function linkCard(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'card_number' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => 'The card Number field is required',
            ], 400);
        }

        $card_number = $request->card_number;
        $card = Card::where('card_number', $card_number)->first();

        if ($card) {
            return response()->json([
                'card' => $card,
            ], 200);
        } else {
            return response()->json([
                'error' => 'Card number does not exist in our database',
            ], 404);
        }
    }
}
