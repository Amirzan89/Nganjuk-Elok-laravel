<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Services\EventController;
use App\Http\Controllers\Services\PentasController;
use App\Http\Controllers\Services\SenimanController;
use App\Http\Controllers\Services\SewaController;
use App\Http\Controllers\Services\TempatController;

use App\Http\Controllers\Page\HomeController AS ShowHomeController;
use App\Http\Controllers\Page\EventController AS ShowEventController;
use App\Http\Controllers\Page\PentasController AS ShowPentasController;
use App\Http\Controllers\Page\SenimanController AS ShowSenimanController;
use App\Http\Controllers\Page\SewaController AS ShowSewaController;
use App\Http\Controllers\Page\TempatController AS ShowTempatController;
use App\Http\Controllers\Page\AdminController AS ShowAdminController;
use App\Http\Controllers\Auth\LoginController;

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
        Route::put('/', [SenimanController::class,'prosesPerpanjangan']);
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
        Route::get('/data', [ShowTempatController::class,'showData']);
        Route::get('/detail/{id}', [ShowTempatController::class,'showDetail']);
        Route::get('/tambah', [ShowTempatController::class,'showTambahTempat']);
        Route::get('/edit/{id}', [ShowTempatController::class,'showEditTempat']);
        Route::get('/{id}', [ShowTempatController::class,'showDetailHome']);
        Route::post('/tambah', [TempatController::class,'tambahTempat']);
        Route::put('/edit',[TempatController::class,'editTempat']);
    });
    //admin route
    Route::group(['prefix'=>'/admin'],function(){
        //page
        Route::get('/',[ShowAdminController::class,'showAdmin']);
        Route::get('/tambah',[ShowAdminController::class,'showAdminTambah']);
        Route::get('/edit/{id}',[ShowAdminController::class,'showAdminEdit']);
        // api
        Route::post('/tambah',[AdminController::class,'tambahAdmin']);
        Route::post('/edit',[AdminController::class,'editAdmin']);
        Route::delete('/delete',[AdminController::class,'hapusAdmin']);
        Route::post('/login',[LoginController::class,'Login']);
        Route::post('/logout',[AdminController::class,'logout']);
        Route::group(['prefix'=>'/kategori_seniman'],function(){
            Route::post('/tambah', [SenimanController::class,'tambahKategoriSeniman']);
            Route::put('/edit', [SenimanController::class,'editKategoriSeniman']);
            Route::delete('/delete', [SenimanController::class,'deleteKategoriSeniman']);
        });
        Route::group(['prefix'=>'/update'],function(){
            Route::put('/profile', [AdminController::class, 'updateProfile']);
            Route::put('/password', [AdminController::class, 'updatePassword']);
        });
    });
    //download only for admin
    Route::group(['prefix'=>'/public'],function(){
        Route::group(['prefix'=>'/download'],function(){
            Route::get('/foto',[AdminController::class,'getFotoProfile'])->name('download.foto');
            Route::get('/foto/{id}',[AdminController::class,'getFotoAdmin']);
        });
    });
    Route::get('/auth/redirect', 'Auth\LoginController@redirectToProvider');
    Route::get('/auth/google', 'Auth\LoginController@handleProviderCallback');
    Route::get('/login', function () {
        return view('page.login');
    })->withoutMiddleware('authorized');
    Route::get('/dashboard',[ShowAdminController::class,'showDashboard']);
    Route::get('/profile',[ShowAdminController::class,'showProfile']);
    Route::get('/event/semua',[ShowHomeController::class,'showHome1'])->withoutMiddleware('authorized');
    Route::group(['prefix'=>'/syarat'],function(){
        Route::get('/event', function () {
            return view('page.home3');
        });
        Route::get('/seniman', function () {
            return view('page.home4');
        });
        Route::get('/sewa', function () {
            return view('page.home5');
        });
        Route::get('/pentas', function () {
            return view('page.home6');
        });
    })->withoutMiddleware('authorized');
    Route::get('/',[ShowHomeController::class,'showHome'])->withoutMiddleware('authorized');
});