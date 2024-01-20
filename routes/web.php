<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Services\EventController;
use App\Http\Controllers\Services\PentasController;
use App\Http\Controllers\Services\SenimanController;
use App\Http\Controllers\Services\SewaController;
use App\Http\Controllers\Services\TempatController;

use App\Http\Controllers\Page\EventController AS ShowEventController;
use App\Http\Controllers\Page\PentasController AS ShowPentasController;
use App\Http\Controllers\Page\SenimanController AS ShowSenimanController;
use App\Http\Controllers\Page\SewaController AS ShowSewaController;
use App\Http\Controllers\Page\TempatController AS ShowTempatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Page\DashboardController;
use App\Http\Middleware\Authorization;

Route::group(['middleware'=>['auth','authorized']],function(){
    //event route
    Route::group(['prefix'=>'/event'],function(){
        Route::get('/',[ShowEventController::class,'showEvent']);
        Route::get('/formulir',[ShowEventController::class,'showFormulir']);
        Route::get('/pengajuan', [ShowEventController::class,'showPengajuan']);
        Route::get('/riwayat', [ShowEventController::class,'showRiwayat']);
        Route::get('/detail/{id}',[ShowEventController::class,'showDetail']);
        Route::put('/pengajuan', [EventController::class,'prosesEvent']);
        Route::put('/riwayat', [EventController::class,'prosesEvent']);
    });
    //seniman route
    Route::group(['prefix'=>'/seniman'],function(){
        Route::get('/',[ShowSenimanController::class,'showSeniman']);
        Route::get('/formulir',[ShowSenimanController::class,'showFormulir']);
        Route::get('/pengajuan', [ShowSenimanController::class,'showPengajuan']);
        Route::get('/riwayat', [ShowSenimanController::class,'showRiwayat']);
        Route::get('/data',[ShowSenimanController::class,'showData']);
        Route::get('/detail/{id}',[ShowSenimanController::class,'showDetailSeniman']);
        Route::put('/pengajuan', [SenimanController::class,'prosesSeniman']);
        Route::put('/riwayat', [SenimanController::class,'prosesSeniman']);
    });
    //perpanjangan route
    Route::group(['prefix'=>'/perpanjangan'],function(){
        Route::get('/', [ShowSenimanController::class,'showPerpanjangan']);
        Route::put('/', [ShowSenimanController::class,'prosesPerpanjangan']);
        Route::get('/detail/{id}',[ShowSenimanController::class,'showDetailPerpanjangan']);
    });
    //pentas route
    Route::group(['prefix'=>'/pentas'],function(){
        Route::get('/',[ShowPentasController::class,'showPentas']);
        Route::get('/formulir',[ShowPentasController::class,'showFormulir']);
        Route::get('/pengajuan', [ShowPentasController::class,'showPengajuan']);
        Route::get('/riwayat', [ShowPentasController::class,'showRiwayat']);
        Route::get('/detail/{id}',[ShowPentasController::class,'showDetail']);
        Route::put('/pengajuan', [PentasController::class,'prosesPentas']);
        Route::put('/riwayat', [PentasController::class,'prosesPentas']);
    });
    //sewa route
    Route::group(['prefix'=>'/sewa'],function(){
        Route::get('/',[ShowSewaController::class,'showSewa']);
        Route::get('/formulir',[ShowSewaController::class,'showFormulir']);
        Route::get('/pengajuan', [ShowSewaController::class,'showPengajuan']);
        Route::get('/riwayat', [ShowSewaController::class,'showRiwayat']);
        Route::get('/detail/{id}',[ShowSewaController::class,'showDetail']);
        Route::put('/pengajuan', [SewaController::class,'prosesSewa']);
        Route::put('/riwayat', [SewaController::class,'prosesSewa']);
    });
    //tempat route
    Route::group(['prefix'=>'/tempat'],function(){
        Route::get('/', [ShowTempatController::class,'showTempat']);
        Route::get('/{id}', [ShowTempatController::class,'showDetailHome']);
        Route::get('/data', [ShowTempatController::class,'showData']);
        Route::get('/detail/{id}', [ShowTempatController::class,'showDetail']);
        Route::get('/tambah', [ShowTempatController::class,'showTambahTempat']);
        Route::get('/edit/{id}', [ShowTempatController::class,'showEditTempat']);
        Route::post('/tambah', [TempatController::class,'tambahTempat']);
        Route::put('/edit',[TempatController::class,'editTempat']);
    });
    //users route
    Route::group(['prefix'=>'/users'],function(){
        Route::post('/login',[LoginController::class,'Login']);
        Route::post('/logout',[AdminController::class,'logout']);
    });
    Route::get('/auth/redirect', 'Auth\LoginController@redirectToProvider');
    Route::get('/auth/google', 'Auth\LoginController@handleProviderCallback');
    Route::get('/login', function () {
        return view('page.login');
    })->withoutMiddleware('authorized');
    Route::get('/dashboard',[DashboardController::class,'show']);
    Route::get('/', function () {
        return view('page.home');
    })->withoutMiddleware('authorized');
    Route::get('/{event?}', function ($event = null) {
        if ($event === 'syarat') {
            return view('page.home3');
        }
        return view('page.home1');
    })->withoutMiddleware('authorized');
    Route::get('/seniman/{seniman?}', function ($seniman = null) {
        if ($seniman === 'syarat') {
            return view('page.home4');
        }
    })->withoutMiddleware('authorized');
    Route::get('/sewa/{sewa?}', function ($sewa = null) {
        if ($sewa === 'syarat') {
            return view('page.home5');
        }
    })->withoutMiddleware('authorized');
    Route::get('/pentas/{pentas?}', function ($pentas = null) {
        if ($pentas === 'syarat') {
            return view('page.home6');
        }
    })->withoutMiddleware('authorized');
});