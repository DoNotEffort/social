<?php
namespace App\Classes;

use Telegram\Bot\Api;
use App\Classes\Base;

class Telegram extends Api
{
		use Base;

		public function __construct($config)
		{
				parent::__construct($config["token"]);
				$this->platform="Telegram";
		}

		public function newPost($text,$config)
		{
			// $not_valid=$this->checkValidate($request);
			// if($not_valid){
			// 	return $not_valid;
			// }
			$returnData=[];
			foreach ($config["chat_ids"] as $chat_id) {
				$returnData[$chat_id]=parent::sendMessage(['chat_id' => $chat_id, 'text' => $text]);
			}
			return $returnData;
		}

}