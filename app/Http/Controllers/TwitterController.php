<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\User;
use \App\Classes\Twitter;

class TwitterController extends Controller
{
    function getOauthUrl(){
     
        $token =Twitter::getRequestToken(config("social.twitter.redirect_uri"));

        //save db
        $tokens=[
            'oauth_token'=>$token['oauth_token'],
            'oauth_token_secret'=>$token['oauth_token_secret'],
        ];

        $DB_USER= request()->user;

        $twitter=$DB_USER->temp_social;
        $twitter["twitter"]=$tokens;

        $DB_USER->temp_social=$twitter;
        $DB_USER->save();

        //create url with oauth token
        $url = Twitter::getAuthenticateUrl($tokens['oauth_token']);

        return response()->json([
            "url"=> $url
        ]);
    }

    function add(){

        $DB_USER= request()->user;
        

        if(!array_key_exists("twitter",$DB_USER->temp_social)){
            return response()->json([
                "message"=>"Invalid request"
            ],400);
        }

        $DB_TOKENS= $DB_USER->temp_social["twitter"];

        $app = Twitter::usingCredentials($DB_TOKENS["oauth_token"],$DB_TOKENS["oauth_token_secret"]);
        $payload = $app->getAccessToken(request('oauth_verifier'));

        //add new data
        $social = $DB_USER->social;
        if(!array_key_exists("twitter",$social)){
            $social["twitter"]=[];
        }
        if(array_key_exists($payload["user_id"],$social["twitter"])){
            return response()->json([
                "message"=>"Account already exists"
            ],400);
        }
        $social["twitter"][$payload["user_id"]]=$payload;
        $DB_USER->social = $social;

        $DB_USER->unset("temp_social.twitter");

        if($DB_USER->temp_social==[]){
            $DB_USER->unset("temp_social");
        }

        $DB_USER->save();
        return "";
    }


    function post(){
        // return response()->json([
        //     "message"=>""
        // ],200);

        // $text = "qqfefwfwwfefwq";
        // $resp=request()->app->newPost( $text );

        // return response()->json($resp);

        $text = request()->text;
        $responseData=[];

        foreach (request()->app as  $appItem) {
            $responseData[$appItem["id"]]=$appItem["app"]->newPost( $text);
        }
        
        return response()->json($responseData);
    }

}
