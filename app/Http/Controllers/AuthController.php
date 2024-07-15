<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private const PAYMENT_STATUS_CREATED = 1;
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

        $userLogged = User::where('is_logged_in', true)
                            ->where('username', '!=', $data['username'])
                            ->first();

        if ($userLogged !== null) {
            return response()->json([
                'message' => 'Another user is already logged in'
            ], 409);
        }

        $user = User::where('username', $data['username'])->first();

        if ($user && $user->payment_status == self::PAYMENT_STATUS_CREATED) {
            return response([
                'message' => 'User not found'
            ], 404);
        }

        if (!$user || !Hash::check($data["password"], $user->password)) {
            return response([
                'message' => 'Bad Credentials'
            ], 401);
        }

        $user->is_logged_in = true;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout(Request $request)
    {
        // Obtém o usuário autenticado
        $user = $request->user();

        // Revoga todos os tokens do usuário
        $user->tokens()->delete();

        $user->is_logged_in = false;
        $user->save();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
