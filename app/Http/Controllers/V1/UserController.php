<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * API of User Register.
     *
     * @return $user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'email'      => 'required|email',
            'password'   => 'required|confirmed',
        ]);

        //checking email exists or not with message
        if (User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email already exists',
                'status'  => 'failed'
            ], 200);
        }
        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);
        $data = [
            'users' => $user
        ];
        return ok("User registered successfully!", $data);
    }

    /**
     * API of User Login.
     *
     * @return $user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        //Check the request of users with email and password
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return error("User with this email is not found!");
        }
        if ($user && Hash::check($request->password, $user->password)) {

            //generate token for users to access
            $token = $user->createToken($request->email)->plainTextToken;

            //Response in json format with success message
            $data = [
                'token'     => $token,
                'users'     => $user,
            ];
            return ok('User Logged in Succesfully', $data);
        } else {
            return error("Password is incorrect");
        }
    }


    /**
     * API of User Logout.
     *
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return ok("Logged out successfully!");
    }
}
