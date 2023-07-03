<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;


class AuthController extends Controller {

    public function login(Request $request) {

        try {
            $this->validate($request, [
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);

            $user = User::with('roles.permissions')->where('email', $request->email)->first();

            if (empty($user) || !Hash::check($request->password, $user->password))
                return $this->respondNotFound('User Kredensial tidak ditemukan');

            $credentials = [
                'tokenType' => 'Bearer',
                'accessToken' => $user->createToken($user->name, ['admin'])->plainTextToken,
                'user' => $user
            ];

            return $this->respond($credentials, 'Berhasil Login.',  200);
        } catch (\Throwable $th) {
            return $this->respondWithError('Terjadi Kesalahan', 500, $th->getMessage());
        }
    }

    public function logout() {
        try {
            if (auth()->user())
                auth()->user()->tokens()->delete();
            return $this->respond('Anda telah berhasil keluar dan session berhasil dihapus',  200);
        } catch (\Throwable $th) {
            return $this->respond('Anda telah berhasil keluar dan session berhasil dihapus',  200);
        }
    }

    public function profile() {
        try {
            if (auth()->user()){
                $user = [
                    'tokenType' => 'Bearer',
                    'accessToken' => auth()->user()->tokens(),
                    'user' => auth()->user()
                ];

                return $this->respond($user, 'Berhasil Login.',  200);
            }
        } catch (\Throwable $th) {
            return $this->respondWithError('User Kredensial tidak ditemukan', 401);
        }

    }
}
