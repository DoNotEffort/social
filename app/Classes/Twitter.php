<?php
namespace App\Classes;

use Atymic\Twitter\Facade\Twitter as TwitterFacade;
use Atymic\Twitter\Exception\ClientException;
use App\Classes\Base;

class Twitter extends TwitterFacade
{
		use Base;

		protected $MIN_LENGTH=1;
		protected $MAX_LENGTH=280;

		public function __construct($config)
		{
				parent::usingCredentials($config["oauth_token"],$config["oauth_token_secret"]);
		}

		public function newPost($text)
		{
			try {
			return parent::postTweet(['status' => $text]);
			} catch (ClientException $e) {
				if($e->getCode()==187){
					  throw new \App\Exceptions\DuplicatePost();
				}
			}
		}
}
