<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/home', 'HomeController@index');
Route::get('/schedules', 'ScheduleController@index')->name('schedules');

Route::get('/appointments', 'AppointmentController@index')->name('appointments');

Route::get('/prescriptions', 'PrescriptionController@index')->name('prescriptions');
Route::get('/prescriptions/show/{id}', 'PrescriptionController@show')->name('showpres');

Route::resource('/tests', 'TestController');
