<?php

use Illuminate\Http\Request;

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


Route::post('/appointment','AppointmentController@store');
Route::get('/appointment/date/avalibility','AppointmentController@staff_date');
Route::delete('/appointment/cancel/{id}','AppointmentController@cancel');
Route::get('/staff/{id}/avalibility/{date}','AppointmentController@staff_avalibility');

Route::post('appointment/modify/{id}','AppointmentController@update');
