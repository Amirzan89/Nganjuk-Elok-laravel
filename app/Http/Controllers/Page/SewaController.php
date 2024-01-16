<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SewaController extends Controller
{
    public function showFormulir(Request $request){
        $dataShow = [
            ''
        ];
        return view('page.tempat.formulir-sewa',$dataShow);
    }
    public function showPengajuan(Request $request){
        $dataShow = [
            ''
        ];
        return view('page.tempat.pengajuan',$dataShow);
    }
    public function showRiwayat(Request $request){
        $dataShow = [
            ''
        ];
        return view('page.tempat.riwayat',$dataShow);
    }
    public function showDetailSewa(Request $request){
        $dataShow = [
            ''
        ];
        return view('page.tempat.detail_sewa',$dataShow);
    }
    public function showDetailTempat(Request $request){
        $dataShow = [
            ''
        ];
        return view('page.home2',$dataShow);
    }
    public function showDetailTempatAdmin(Request $request){
        $dataShow = [
            ''
        ];
        return view('page.tempat.detail_tempat',$dataShow);
    }
    public function showTambahTempat(Request $request){
        $dataShow = [
            ''
        ];
        return view('page.tempat.tambah_tempat',$dataShow);
    }
    public function showEditTempat(Request $request){
        $dataShow = [
            ''
        ];
        return view('page.tempat.edit_detail_tempat',$dataShow);
    }
}