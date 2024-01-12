<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\TempatController;

use App\Http\Controllers\Mobile\EventController AS MobileEventController;
use App\Http\Controllers\Mobile\PentasController AS MobilePentasController;
use App\Http\Controllers\Mobile\TempatController AS MobileTempatController;
use App\Http\Controllers\Mobile\SenimanController AS MobileSenimanController;

use App\Http\Controllers\Page\EventController AS ShowEventController;
use App\Http\Controllers\Page\PentasController AS ShowPentasController;
use App\Http\Controllers\Page\SenimanController AS ShowSenimanController;
use App\Http\Controllers\Page\TempatController AS ShowTempatController;

use App\Http\Controllers\Auth\LoginController;
Route::group(['middleware'=>'auth'],function(){
    Route::get('/login', function () {
        return view('page.login');
    })->name('login');
    Route::get('/dashboard', function () {
        return view('page.dashboard');
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
    Route::group(['prefix'=>'/sewa'],function(){
        Route::get('/formulir',[ShowTempatController::class,'showFormulir']);
        Route::get('/detail',[ShowTempatController::class,'showDetailSewa']);
    });
    Route::group(['prefix'=>'/tempat'],function(){
        Route::get('/', [ShowTempatController::class,'showTempat']);
        Route::get('/data', [ShowTempatController::class,'showDataTempat']);
        Route::get('/pengajuan', [ShowTempatController::class,'showPengajuan']);
        Route::get('/riwayat', [ShowTempatController::class,'showRiwayat']);
        Route::get('/detail', [ShowTempatController::class,'showDetailTempat']);
        Route::get('/lihat', [ShowTempatController::class,'showDetailTempat']);
        Route::get('/tambah', [ShowTempatController::class,'showTambahTempat']);
        Route::get('/edit', [ShowTempatController::class,'showEditTempat']);
        Route::post('/tambah', [TempatController::class,'tambah']);
        Route::post('/edit',[TempatController::class,'edit']);
    });
    Route::group(['prefix'=>'/users'],function(){
        Route::post('/login',[LoginController::class,'Login']);
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