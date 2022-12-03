<?php

namespace App\Http\Middleware\Authenticate;

use Closure;
use Illuminate\Http\Request;

use \App\Models\User;

class ApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $API_TOKEN = $request->header('Authorization');

        if ($API_TOKEN) {
            $request->user = User::where('api_token', $API_TOKEN)->first();

            if (!$request->user)
                return response()->json([
                    'message' => 'Unauthorized token'
                ], 401);
        }
        
        return $next($request);
    }
}
