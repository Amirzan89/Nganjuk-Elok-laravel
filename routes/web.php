<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
Route::group(['middleware'=>'auth'],function(){
    Route::get('/login', function () {
        return view('page.login');
    })->name('login');
    Route::get('/dashboard', function () {
        return view('page.dashboard');
    });
    Route::get('/tempat', function () {
        return view('page.tempat');
    });
    Route::get('/event', function () {
        return view('page.event');
    });
    Route::get('/pentas', function () {
        return view('page.pentas');
    });
    Route::get('/seniman', function () {
        return view('page.seniman');
    });
    Route::get('/', function () {
        return view('page.home');
    });
    Route::get('/{event?}', function ($event = null) {
        if ($event === 'syarat') {
            return view('page.home3');
        }
        return view('page.home1');
    });
    Route::get('/seniman/{seniman?}', function ($seniman = null) {
        if ($seniman === 'syarat') {
            return view('page.home4');
        }
    });
    Route::get('/sewa/{sewa?}', function ($sewa = null) {
        if ($sewa === 'syarat') {
            return view('page.home5');
        }
    });
    Route::get('/pentas/{pentas?}', function ($pentas = null) {
        if ($pentas === 'syarat') {
            return view('page.home6');
        }
    });
    
    Route::group(['prefix'=>'/event'],function(){
        //
    });
    Route::group(['prefix'=>'/seniman'],function(){
        //
    });
    Route::group(['prefix'=>'/pentas'],function(){
        //
    });
    Route::group(['prefix'=>'/tempat'],function(){
        //
    });
    Route::group(['prefix'=>'/users'],function(){
        Route::post('/login',[LoginController::class,'Login']);
        // Route::post('/login','Auth\LoginController@Login');
    });
    Route::get('/auth/redirect', 'Auth\LoginController@redirectToProvider');
    Route::get('/auth/google', 'Auth\LoginController@handleProviderCallback');
});
Route::group(['prefix'=>'/mobile','middleware'=>'authorized'],function(){
    Route::group(['prefix'=>'/users'],function(){
        //
    });
    Route::group(['prefix'=>'/event'],function(){
        //
    });
    Route::group(['prefix'=>'/seniman'],function(){
        //
    });
    Route::group(['prefix'=>'/pentas'],function(){
        //
    });
    Route::group(['prefix'=>'/tempat'],function(){
        //
    });
});