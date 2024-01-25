<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\MasyarakatController;
use App\Http\Controllers\Mobile\Services\MailController;
use App\Http\Controllers\Mobile\Services\EventController AS MobileEventController;
use App\Http\Controllers\Mobile\Services\PentasController AS MobilePentasController;
use App\Http\Controllers\Mobile\Services\SewaController AS MobileSewaController;
use App\Http\Controllers\Mobile\Services\TempatController AS MobileTempatController;
use App\Http\Controllers\Mobile\Services\SenimanController AS MobileSenimanController;
use App\Http\Controllers\Mobile\Services\PerpanjanganController AS MobilePerpanjanganController;

use App\Http\Controllers\Mobile\Auth\LoginController AS MobileLoginController;
use App\Http\Controllers\Mobile\Auth\RegisterController AS MobileRegisterController;

Route::group(['prefix'=>'/mobile','middleware'=>'authorized'],function(){
    Route::group(['prefix'=>'/send'],function(){
        Route::group(['prefix'=>'/create'],function(){
            Route::post('/password','Mail\MailController@createForgotPassword');
            Route::post('/email','Mail\MailController@createVerifyEmail');
        });
        Route::group(['prefix'=>'/password'],function(){
            Route::get('/{any?}','UserController@getChangePass')->where('any','.*');
            Route::post('/','UserController@changePassEmail');
        });
        Route::group(['prefix'=>'/email'],function(){
            Route::get('/{any?}','UserController@verifyEmail')->where('any','.*');
            Route::post('/','UserController@verifyEmail');
        });
        Route::group(['prefix'=>'/otp'],function(){
            Route::post('/password','UserController@getChangePass');
            Route::post('/email','UserController@verifyEmail');
        });
    });
    Route::group(['prefix'=>'/users'],function(){
        Route::post('/login', [MobileLoginController::class,'Login']);
        Route::post('/login/google', [MobileLoginController::class,'LoginGoogle']);
        Route::post('/register', [MobileRegisterController::class,'Register']);
        Route::put('/update', [MasyarakatController::class,'updateUser']);
        Route::post('/logout', [MasyarakatController::class,'logout']);
    });
    Route::group(['prefix'=>'/event'],function(){
        Route::post('/tambah', [MobileEventController::class,'tambahEvent']);
        Route::post('/edit', [MobileEventController::class,'editEvent']);
        Route::delete('/delete', [MobileEventController::class,'hapusEvent']);
    });
    Route::group(['prefix'=>'/seniman'],function(){
        Route::post('/tambah', [MobileSenimanController::class,'tambahSeniman']);
        Route::post('/edit', [MobileSenimanController::class,'editSeniman']);
        Route::delete('/delete', [MobileSenimanController::class,'hapusSeniman']);
    });
    Route::group(['prefix'=>'/perpanjangan'],function(){
        Route::post('/tambah', [MobilePerpanjanganController::class,'tambahPerpanjangan']);
        Route::post('/edit', [MobilePerpanjanganController::class,'editPerpanjangan']);
        Route::delete('/delete', [MobilePerpanjanganController::class,'hapusPerpanjangan']);
    });
    Route::group(['prefix'=>'/pentas'],function(){
        Route::post('/tambah', [MobilePentasController::class,'tambahPentas']);
        Route::post('/edit', [MobilePentasController::class,'editPentas']);
        Route::delete('/delete', [MobilePentasController::class,'hapusPentas']);
    });
    Route::group(['prefix'=>'/tempat'],function(){
        //
    });
    Route::group(['prefix'=>'/sewa'],function(){
        Route::post('/tambah', [MobileSewaController::class,'pengajuanSewa']);
        Route::post('/edit', [MobileSewaController::class,'editSewa']);
        Route::delete('/delete', [MobileSewaController::class,'hapusSewa']);
    });
});