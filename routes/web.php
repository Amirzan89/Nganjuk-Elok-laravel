<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Services\EventController;
use App\Http\Controllers\Services\PentasController;
use App\Http\Controllers\Services\SenimanController;
use App\Http\Controllers\Services\SewaController;
use App\Http\Controllers\Services\TempatController;

use App\Http\Controllers\Mobile\EventController AS MobileEventController;
use App\Http\Controllers\Mobile\PentasController AS MobilePentasController;
use App\Http\Controllers\Mobile\TempatController AS MobileTempatController;
use App\Http\Controllers\Mobile\SenimanController AS MobileSenimanController;

use App\Http\Controllers\Page\EventController AS ShowEventController;
use App\Http\Controllers\Page\PentasController AS ShowPentasController;
use App\Http\Controllers\Page\SenimanController AS ShowSenimanController;
use App\Http\Controllers\Page\SewaController AS ShowSewaController;
use App\Http\Controllers\Page\TempatController AS ShowTempatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Page\DashboardController;

Route::group(['middleware'=>'auth'],function(){
    Route::get('/login', function () {
        return view('page.login');
    })->name('login');
    Route::get('/dashboard',[DashboardController::class,'show']);
    Route::get('/pentas', function () {
        return view('page.pentas');
    });
    Route::get('/seniman', function () {
        return view('page.seniman');
    });
    Route::group(['prefix'=>'/event'],function(){
        Route::get('/',[ShowEventController::class,'showEvent']);
        Route::get('/formulir',[ShowEventController::class,'showFormulir']);
        Route::get('/pengajuan', [ShowEventController::class,'showPengajuan']);
        Route::get('/riwayat', [ShowEventController::class,'showRiwayat']);
        Route::get('/detail/{id}',[ShowEventController::class,'showDetail']);
        Route::put('/pengajuan', [EventController::class,'prosesEvent']);
        Route::put('/riwayat', [EventController::class,'prosesEvent']);
    });
    Route::group(['prefix'=>'/seniman'],function(){
        //
    });
    Route::group(['prefix'=>'/pentas'],function(){
        //
    });
    Route::group(['prefix'=>'/sewa'],function(){
        Route::get('/',[ShowSewaController::class,'showSewa']);
        Route::get('/formulir',[ShowSewaController::class,'showFormulir']);
        Route::get('/pengajuan', [ShowSewaController::class,'showPengajuan']);
        Route::get('/riwayat', [ShowSewaController::class,'showRiwayat']);
        Route::get('/detail/{id}',[ShowSewaController::class,'showDetail']);
        Route::put('/pengajuan', [SewaController::class,'prosesSewa']);
        Route::put('/riwayat', [SewaController::class,'prosesSewa']);
    });
    Route::group(['prefix'=>'/tempat'],function(){
        Route::get('/', [ShowTempatController::class,'showTempat']);
        Route::get('/data', [ShowTempatController::class,'showDataTempat']);
        Route::get('/detail', [ShowTempatController::class,'showDetailTempat']);
        Route::get('/lihat', [ShowTempatController::class,'showDetailTempat']);
        Route::get('/tambah', [ShowTempatController::class,'showTambahTempat']);
        Route::get('/edit', [ShowTempatController::class,'showEditTempat']);
        Route::post('/tambah', [TempatController::class,'tambah']);
        Route::put('/edit',[TempatController::class,'edit']);
    });
    Route::group(['prefix'=>'/users'],function(){
        Route::post('/login',[LoginController::class,'Login']);
        Route::post('/logout',[AdminController::class,'logout']);
    });
    Route::get('/auth/redirect', 'Auth\LoginController@redirectToProvider');
    Route::get('/auth/google', 'Auth\LoginController@handleProviderCallback');
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
