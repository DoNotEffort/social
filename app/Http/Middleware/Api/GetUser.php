<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

use \App\Models\User;

class GetUser
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
        // $request->user=(object) [
        //     "id"=>1,
        //     "name"=>"John Doe"
        // ];

        // $request->socialQuery=User::where('user_id',$request->user->id);

        if (env('APP_ENV') === 'production') {
            $request->PLATFORM = $request->segment(1);
            $request->PROCESS = $request->segment(2);
    
        }else{
            $request->PLATFORM = $request->segment(2);
            $request->PROCESS = $request->segment(3);
        }
        $request->custom_options=[];
        return $next($request);
    }
}
