<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\Telegram\AddAccountRequest;

use Illuminate\Support\Arr;

class TelegramController extends Controller
{    
    function addAccount(AddAccountRequest $request){
        $TOKEN=$request->token;

        $app = new \App\Classes\Telegram(["token"=>$TOKEN]);

        $response = $app->getMe();
        
        $responseData = json_decode(json_encode($response), true);
        $responseDataForDB =  array_intersect_key(
            $responseData,
            array_flip(["id","first_name","username","is_bot"])
        ); 

        $DBData =$responseDataForDB;
        $DBData["token"]=$TOKEN;

        $DB_USER= request()->user;
        $social = $DB_USER->social;

        if(Arr::exists($social,"telegram") && Arr::exists($social["telegram"],$DBData["id"])){
            return response()->json([
                "message"=>"Account already exists"
            ],400);
        }

        $social["telegram"][$DBData["id"]]=$DBData;
        $DB_USER->social = $social;
        $DB_USER->save();


        return response()->json($responseData);
    }
    

    function getMe()
    {
        $response = request()->app->getMe();
        $botId = $response->id;
        $firstName = $response->first_name;
        $username = $response->username;

        return response()->json([
            "botId"=>$botId,
            "firstName"=>$firstName,
            "username"=>$username
        ]);
    }


    function sendMessage(){
        $text = request()->text;
        $responseData=[];

        foreach (request()->app as  $appItem) {
            $config = $appItem["config"];
            $responseData[$appItem["id"]]=$appItem["app"]->newPost( $text, $config );
        }
        return response()->json(
           $responseData
        );
    }
}
