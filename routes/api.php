<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\Services\EventController AS MobileEventController;
use App\Http\Controllers\Mobile\Services\PentasController AS MobilePentasController;
use App\Http\Controllers\Mobile\Services\SewaController AS MobileSewaController;
use App\Http\Controllers\Mobile\Services\TempatController AS MobileTempatController;
use App\Http\Controllers\Mobile\Services\SenimanController AS MobileSenimanController;
use App\Http\Controllers\Mobile\Auth\LoginController AS MobileLoginController;
use App\Http\Controllers\Mobile\Auth\RegisterController AS MobileRegisterController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['prefix'=>'/mobile','middleware'=>'authorized'],function(){
    Route::group(['prefix'=>'/users'],function(){
        Route::post('/login', [MobileLoginController::class,'Login']);
        Route::post('/register', [MobileRegisterController::class,'Login']);
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