<?php

namespace App\Http\Middleware\Social;

use Closure;
use Illuminate\Http\Request;

use \App\Classes\Telegram;

use Illuminate\Support\Arr;

class TelegramMiddleware
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
        $accounts = request()->accounts;

        $user= $request->user;

        $request->app =[];
        foreach (array_keys($accounts) as $accountId) {
            $token=Arr::get($user->social,'telegram.'.$accountId.'.token',null);

            if(!$token)
                continue;
            $request->app[$accountId]["id"] = $accountId;
            $request->app[$accountId]["app"] = new Telegram(["token"=>$token]);
            $request->app[$accountId]["config"] =$accounts[$accountId];
        }
        return $next($request);
    }
}
