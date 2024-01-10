<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>'auth'],function(){
    // Route::get('/', function () {
    //     return view('welcome');
    // });
    Route::get('/', function () {
        return view('home');
    });
    Route::get('?event', function () {
        return view('home1');
    });
    Route::get('?event=syarat', function () {
        return view('home3');
    });
    Route::get('?seniman=syarat', function () {
        return view('home4');
    });
    Route::get('?sewa=syarat', function () {
        return view('home5');
    });
    Route::get('?pentas=syarat', function () {
        return view('home6');
    });
    Route::get('/login', function () {
        return view('login');
    });
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    Route::get('/tempat', function () {
        return view('tempat');
    });
    Route::get('/event', function () {
        return view('event');
    });
    Route::get('/pentas', function () {
        return view('pentas');
    });
    Route::get('/seniman', function () {
        return view('seniman');
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