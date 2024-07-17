<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255', 'min:3'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

    }
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                // for registration we want to be specific which fields are missing
                'errors' => $validator->errors()
            ], 200); // HTTP status code 200 because we need to show this
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // create a auth token for the user
        $authToken = $user->createToken('auth-token')->plainTextToken;
        return response()->json([
            'status' => true,
            'message' => 'Successfully registered',
            'access_token' => $authToken,
        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $credentials = request(['email', 'password']);
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'The given input doesn\'t match with our records',
// we could send these errors, but an attacker will know which one is wrong and then he may try bruteforce
//                'errors' => [
//                    'password' => [
//                        'Invalid credentials'
//                    ],
//                ]
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $authToken = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => "Successfully logged in",
            'access_token' => $authToken,
        ]);
    }
}
