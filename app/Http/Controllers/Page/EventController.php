<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\DetailEvents;
class EventController extends Controller
{
    public function showEvent(Request $request){
        $dataShow = [
            'totalPengajuan'=>'',
            'totalRiwayat'=>'',
        ];
        return view('page.event.event',$dataShow);
    }
    public function showFormulir(Request $request){
        $dataShow = [
            'totalPengajuan'=>'',
            'totalRiwayat'=>'',
        ];
        return view('page.event.formulir',$dataShow);
    }
    public function showPengajuan(Request $request){
        $dataShow = [
            'totalPengajuan'=>'',
            'totalRiwayat'=>'',
        ];
        return view('page.event.pengajuan',$dataShow);
    }
    public function showRiwayat(Request $request){
        $dataShow = [
            'totalPengajuan'=>'',
            'totalRiwayat'=>'',
        ];
        return view('page.event.riwayat',$dataShow);
    }
    public function showDetail(Request $request){
        $dataShow = [
            ''=>'',
            ''=>'',
        ];
        return view('page.event.detail_event',$dataShow);
    }
}