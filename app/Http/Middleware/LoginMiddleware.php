<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->input('token')) {
            $check =  User::where('token', $request->input('token'))->first();

            if (!$check) {
                $out = [
                    "message" => "Token Tidak Valid",
                    "code" => 401
                ];
                return response()->json($out, $out['code']);
            } else {
                return $next($request);
            }
        } else {
            $out = [
                "message" => "Masukkan Token",
                "code" => 401
            ];
            return response()->json($out, $out['code']);
        }
    }
}