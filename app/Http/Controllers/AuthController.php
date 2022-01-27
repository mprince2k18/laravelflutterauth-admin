<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;

class AuthController extends Controller
{

    /**
     * register
     */
    public function register(Request $request)
    {
        // validate request
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]
        );

        // store user
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        // return user & response token in response
        return response()->json(
            [
                'user' => $user,
                'token' => $user->createToken('secret')->plainTextToken
            ]
        );
    }


    /**
     * LOGIN
     */
    public function login(Request $request)
    {
        // validate request
        $attrs = $request->validate(
            [
                'email' => 'required|string|email',
                'password' => 'required|string|min:6',
            ]
        );

        // attempt login
        if (!Auth::attempt($attrs)) {
            return response(['message' => 'Invalid credentials'], 403);
        }

        // return user & response token in response
        return response()->json(
            [
                'user' => auth()->user(),
                'token' => auth()->user()->createToken('secret')->plainTextToken
            ], 200
        );
    }

    /**
     * LOGOUT
     */
    public function logout()
    {
        // logout
        auth()->user()->tokens()->delete();

        // return response
        return response([
            'message' => 'Successfully logged out'
        ], 200);
    }

    /**
     * USER DETAILS
     */
    public function user_details()
    {
        // return response
        return response([
            'user' => auth()->user()
        ], 200);
    }

    //ENDS
}
