<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\TwitterController;
use \App\Http\Controllers\TelegramController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//COLLECTIVE
Route::group(['prefix' => 'collective', 'middleware' => ['collective', 'collective.response.modify']], function () {
    Route::post('post', [CollectiveController::class, 'sendMessage']);
});

//TELEGRAM
Route::group(['prefix' => 'telegram'], function () {
    Route::post('add', [TelegramController::class, 'addAccount']);

    Route::group(['middleware' => ['telegram',"response.modify"]], function () {
        Route::post('post', [TelegramController::class, 'sendMessage']);
    });

});

//TWITTER
Route::group(['prefix' => 'twitter'], function () {

    Route::get('oauth', [TwitterController::class, 'getOauthUrl']);
    Route::get('add', [TwitterController::class, 'add'])->name('twitter.callback');

    Route::group(['middleware' => ['twitter',"response.modify"]], function () {
        Route::post('post', [TwitterController::class, 'post']);
    });

});
