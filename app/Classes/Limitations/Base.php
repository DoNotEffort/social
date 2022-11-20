<?php
namespace App\Classes\Limitations;

use Illuminate\Support\Facades\Response;

use \App\Exceptions\ApiValidationException;

trait Base
{

	public function validate($text,$length_reached="error")
	{
		$errors=$this->validateLength($text);

		 if($errors){
				if($length_reached=="error"){
					throw new ApiValidationException($errors[0]);
			}

			if($length_reached=="ignore"){
				return [
					"status"=>"ignored",
					"message"=>$errors[0]
				];
			}

		 }

	

	}

		public function validateLength($text)
		{
				$length=strlen($text);
				$errors=[];
				if($length<$this->MIN_LENGTH){
					$errors[]= "Text is too short";
				}
				if($length>$this->MAX_LENGTH){
					$errors[]= "Text is too long";
				}

				return count($errors)==0
					? false
					: $errors;

			}
}