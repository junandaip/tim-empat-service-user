<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use \Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }

    #Cek data user. Service kita ngga ada fungsi get user, jadi bisa dihapus
    public function index()
    {
        return User::all();
    }

    public function getUsername($username)
    {
        $user = User::where('username', $username)->first();
        if ($user) {
            return
                response()->json([
                    'message' => 'Get Data by Username',
                    'data' => $user
                ], 201);
        } else {
            return response()->json([
                'message' => 'User Not Found',
            ], 404);
        }
    }

    public function put(Request $request, $username)
    {
        try {
            $user = User::findOrFail($username);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->fill(
            $request->only(['kondisi'])
        );

        $user->save();

        return response()->json([
            'created' => true,
            'data' => $user
        ], 200);
    }
}
