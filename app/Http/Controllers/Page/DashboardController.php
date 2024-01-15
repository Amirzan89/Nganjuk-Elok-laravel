<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Events;
use App\Models\Seniman;
use App\Models\SewaTempat;
use App\Models\SuratAdvis;
use App\Models\Perpanjangan;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function show(Request $request){
        $totalAdmin = User::whereNotIn('role', ['super admin', 'masyarakat'])->count();
        $totalPengguna = User::where('role','masyarakat')->count();
        $dataKalenderQuery = SewaTempat::select(
            'id_sewa',
            'nama_peminjam',
            'nama_tempat',
            'nama_kegiatan_sewa',
            DB::raw('DATE(tgl_awal_peminjaman) AS start_date'),
            DB::raw('DATE(tgl_akhir_peminjaman) AS end_date')
        )->where('status', 'diajukan')->get();
        foreach($dataKalenderQuery as $data){
            $sewa = array(
                'id' => $data['id_sewa'],
                'title' => $data['nama_kegiatan_sewa'],
                'peminjam' => $data['nama_peminjam'],
                'nama_tempat' => $data['nama_tempat'],
                'start' => $data['start_date'],
                'end' => $data['end_date'],
            );
            $dataKalender[] = $sewa;
        }
        unset($dataKalenderQuery);
        $totalEvent = Events::where('status','diajukan')->count();
        $totalSeniman = Seniman::where('status','diajukan')->count();
        $totalSewa = SewaTempat::where('status','diajukan')->count();
        $totalPentas = SuratAdvis::where('status','diajukan')->count();
        $totalPerpanjangan = Perpanjangan::where('status','diajukan')->count();
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'totalAdmin'=>$totalAdmin,
            'totalPengguna'=>$totalPengguna,
            'dataKalender'=>$dataKalender,
            'totalEvent'=>$totalEvent,
            'totalSeniman'=>$totalSeniman,
            'totalSewa'=>$totalSewa,
            'totalPentas'=>$totalPentas,
            'totalPerpanjangan'=>$totalPerpanjangan,
        ];
        return view('page.dashboard',$dataShow);
    }
}