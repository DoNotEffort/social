<?php
namespace App\Classes\Limitations;
use App\Classes\Limitations\Base;

class Telegram
{
	use Base;
	protected $MIN_LENGTH=1;
	protected $MAX_LENGTH=4096;
}