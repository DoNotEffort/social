<?php
namespace App\Classes;

trait Base
{
	private function checkValidate($request)
	{
		if($request->custom_options["length_reached"]=="ignore"){
			$class="\App\Classes\Limitations\\".ucfirst($request->PLATFORM);
			$classItem=new $class();
			$response=$classItem->validate($request,$request->custom_options["length_reached"]);

			if(gettype($response)!="array" || !array_key_exists("status",$response)){
				return false;
			}

			if($response["status"]=="ignored"){
					return ["status"=>false,"message"=>$response["message"]];
			}

		}
	}
}