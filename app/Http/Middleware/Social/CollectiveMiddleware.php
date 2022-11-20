<?php

namespace App\Http\Middleware\Social;

use Closure;
use Illuminate\Http\Request;

use \App\Classes\Telegram;
use \App\Classes\Twitter;

class CollectiveMiddleware
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
        // user_id | social=>[telegram:id,facebook:id]
        $request->apps=[];

        $social=$request->social;

        $user=$request->socialQuery->first();




        foreach ($social as  $platformName=>$socialValues) {
            
            if (!in_array($platformName,array_keys(config("social")))) {
                echo "$platformName not found";
                continue;
            }

            $model_name = "\App\Classes\\".ucfirst($platformName);
        
            $counter=1;

            foreach ($socialValues as $socialId) {

                $name=count($socialValues)>1
                    ? $platformName."_".$counter
                    : $platformName;

                $appConfigPath="social.$platformName.tokens";

                $appConfig=config($appConfigPath, []);

                $configs=array_merge(
                    $user['social'][$platformName][$socialId],
                    $appConfig
                );
           
                $request->apps[$name]["id"]= $socialId;
                $request->apps[$name]["app"] = new $model_name($configs);

                    $request->appOptions = array_key_exists($platformName,$request->options)
                     ? $request->options[$platformName][$socialId]
                     : [];
                
                
                $counter++;
            }
          
        }


        return $next($request);
    }
}
