<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('attachments')->group(function(){
    Route::delete('/{attachment}','AttachmentController@destroy')->name('attachments.destroy');
    Route::post('/{screen}','AttachmentController@store')->name('attachments.store');
    Route::get('/{screen}','AttachmentController@index')->name('attachments.index');
});
Route::get('logout','UserController');
Route::apiResource('screens', 'ScreenController');
Route::apiResource('messages', 'MessageController');

