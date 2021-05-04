<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use \Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{

    #Cek data user. Service kita ngga ada fungsi get user, jadi bisa dihapus
    public function index()
    {
        return User::all();
    }

    public function post(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
            'kondisi' => 'required'
        ]);

        $user = User::create(
            $request->only(['username', 'password', 'role', 'kondisi'])
        );

        return response()->json([
            'updated' => true,
            'data' => $user
        ], 200);
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
            $request->only(['username', 'password', 'role', 'kondisi'])
        );

        $user->save();

        return response()->json([
            'created' => true,
            'data' => $user
        ], 200);
    }

    public function destroy($username)
    {
        try {
            $user = User::findOrFail($username);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'User not found'
                ]
            ], 404);
        }

        $user->delete();

        return response()->json([
            'deleted' => true
        ], 200);
    }
}
