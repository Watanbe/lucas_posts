<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $data = $request->validate([
            'username' => ['required', 'unique:users'],
            'password' => ['required', 'min:6'],
            'chat_id' => ['required', 'string'],
            'payment_status' => ['required', 'integer'],
            'payment_checkout_uri' => ['required', 'string'],
            'payment_id' => ['required', 'string'],
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function resetFromBot(Request $request, $id) {
        $data = $request->validate([
            'password' => ['required', 'min:6'],
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::find($id);

        if (!$user) {
            return response([
                'message' => 'User not found'
            ], 404);
        }

        $user->password = $data['password'];
        $user->save();

        return $user;
    }

    public function login(Request $request) {
        $data = $request->validate([
            'username' => ['required', 'exists:users'],
            'password' => ['required', 'min:6']
        ]);

        $user = User::where('username', $data['username'])->first();

        if (!$user || !Hash::check($data["password"], $user->password)) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
