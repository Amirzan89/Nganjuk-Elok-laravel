<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ListTempat;
class TempatController extends Controller
{
    public function showTempat(Request $request){
        $tempatData = $this->changeMonth(ListTempat::select('id_tempat, nama_tempat, alamat_tempat, deskripsi_tempat')->get());
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'tempatData'=>$tempatData,
        ];
        return view('page.tempat.tempat',$dataShow);
    }
    public function showData(Request $request){
        $tempatData = ListTempat::select('id_tempat', 'nama_tempat', 'alamat_tempat', 'deskripsi_tempat')->get();
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'tempatData'=>$tempatData,
        ];
        return view('page.tempat.data',$dataShow);
    }
    //for home page
    public function showDetailHome(Request $request, $tempatId){
        $tempatData = ListTempat::select('nama_tempat', 'alamat_tempat', 'deskripsi_tempat','foto_tempat')->where('id_tempat', $tempatId)->limit(1)->get()[0];
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'tempatData'=>$tempatData,
        ];
        return view('page.home2',$dataShow);
    }
    //only for admin and super admin
    public function showDetail(Request $request, $tempatId){
        $tempatData = ListTempat::select()->where('id_tempat', $tempatId)->limit(1)->get()[0];
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'tempatData'=>$tempatData,
        ];
        return view('page.tempat.detail',$dataShow);
    }
    public function showTambahTempat(Request $request){
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
        ];
        return view('page.tempat.tambah',$dataShow);
    }
    public function showEditTempat(Request $request, $tempatId){
        $tempatData = ListTempat::select()->where('id_tempat', $tempatId)->limit(1)->get()[0];
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'tempatData'=>$tempatData,
        ];
        return view('page.tempat.edit',$dataShow);
    }
}