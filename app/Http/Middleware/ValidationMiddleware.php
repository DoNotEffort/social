<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;

use \App\Exceptions\ApiValidationException;

class ValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    private function lengthReachedError($request,$PLATFORM){
        $limitation="\App\Classes\Limitations\\".ucfirst($PLATFORM);
        $limitation=new $limitation();
        $validation=$limitation->validate($request);
        if($validation!==true){
            throw new ApiValidationException($validation);
        }
        return true;
    }

    public function handle(Request $request, Closure $next)
    {
        $options=$request->options;

        $length_reached_config=[
            "values"=> [
                "split","ignore","error","error_all"
            ],
            "default"=>"split"
        ];

        $length_reached=Arr::get((array) $options,"length_reached",$length_reached_config["default"]);


        if(!in_array($length_reached, $length_reached_config["values"])){
            $length_reached=$length_reached_config["default"];
        }

        $request->custom_options["length_reached"]=$length_reached;


        if($request->PLATFORM==="collective" && $length_reached==="error_all"){
            $platformNames=array_keys($request->social);
            foreach ($platformNames as  $platformName) {
                $this->lengthReachedError($request,$platformName);
            }
        }

        //----------------------

        $error_allowed_items=["error","error_all","ignored"];

        if($request->PLATFORM!=="collective" && in_array($length_reached,$error_allowed_items)){
            $this->lengthReachedError($request,$request->PLATFORM);
        }
        return $next($request);
        
    }
}
