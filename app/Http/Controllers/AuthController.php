<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class AuthController extends Controller
{

    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input("username");
        $password = $request->input("password");

        $hashPwd = Hash::make($password);

        $data = [
            "username" => $username,
            "password" => $hashPwd,
            "role" => 1,
            "kondisi" => 1
        ];

        if (User::create($data)) {
            $out = [
                "message" => "register_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "register_failed",
                "code"   => 404,
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input("username");
        $password = $request->input("password");

        $user = User::where("username", $username)->first();

        if (!$user) {
            $out = [
                "message" => "login_failed",
                "code"    => 401,
                "result"  => [
                    "token" => null,
                ]
            ];
            return response()->json($out, $out['code']);
        }

        if (Hash::check($password, $user->password)) {
            $newtoken  = $this->generateRandomString();

            $user->update([
                'token' => $newtoken
            ]);

            $out = [
                "message" => "login_success",
                "code"    => 200,
                "result"  => [
                    "token" => $newtoken,
                    "kondisi" => $user->kondisi,
                    "role" => $user->role,
                ]
            ];
        } else {
            $out = [
                "message" => "login_failed",
                "code"    => 401,
                "result"  => [
                    "token" => null,
                ]
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function logout(Request $request)
    {
        $session = $request->session();
        $this->validate($request, [
            'username' => 'required',
        ]);

        $username = $request->input("username");
        try {
            $user = User::where("username", $username)->first();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Error session'
            ], 404);
        }

        $user->update([
            'token' => null
        ]);

        $session->flush();

        return response()->json([
            'message' => "logout_success",
        ], 200);
    }

    function generateRandomString($length = 80)
    {
        $karakkter = '012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $panjang_karakter = strlen($karakkter);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $karakkter[rand(0, $panjang_karakter - 1)];
        }
        return $str;
    }
}
