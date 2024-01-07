<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>'auth'],function(){
    Route::get('/', function () {
        return view('welcome');
    });
    Route::group(['prefix'=>'/users/'],function(){
        Route::post('/login','Auth\LoginController@Login');
        Route::post('/register','Auth\RegisterCOntroller@register');
    });
});
Route::group(['prefix'=>'/mobile'],function(){
    Route::group(['prefix'=>'/users'],function(){
        //
    });
});