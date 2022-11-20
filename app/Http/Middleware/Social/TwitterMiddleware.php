<?php

namespace App\Http\Middleware\Social;

use Closure;
use Illuminate\Http\Request;

use \App\Classes\Twitter;

class TwitterMiddleware
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
          
        $DB_USER=(object) $request->socialQuery->select("social")->first();
        $request->app =[];
        if ($accounts) {
            foreach (array_keys($accounts) as $accountId) {
            if(!array_key_exists("twitter",$DB_USER->social)){
                continue ;
            }
            $DB_TOKENS= $DB_USER->social["twitter"][$accountId];
                $request->app[$accountId]["id"] = $accountId;
                $request->app[$accountId]["app"] =  new Twitter([
                    "oauth_token"=>$DB_TOKENS["oauth_token"],
                    "oauth_token_secret"=>$DB_TOKENS["oauth_token_secret"]
                ]);
            }
        }
        
        return $next($request);
    }
}
