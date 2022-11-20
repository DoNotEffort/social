<?php

namespace App\Exceptions;

use Exception;

class DuplicatePost extends Exception
{
    public function render()
    {
        return response()->json(  [
            "message"=>"Duplicate post"
        ],409);
    }
}
