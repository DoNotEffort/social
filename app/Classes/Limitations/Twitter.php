<?php
namespace App\Classes\Limitations;
use App\Classes\Limitations\Base;

class Twitter
{
	use Base;
	protected $MIN_LENGTH=1;
	protected $MAX_LENGTH=280;
}