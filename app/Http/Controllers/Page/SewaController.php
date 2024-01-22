<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\ListTempat;
use App\Models\SewaTempat;
use DateTime;
class SewaController extends Controller
{
    private function changeMonth($inpDate){
        $inpDate = json_decode($inpDate, true);
        $monthTranslations = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        // Check if it's an associative array (single data)
        if (array_keys($inpDate) !== range(0, count($inpDate) - 1)) {
            foreach (['tanggal_awal', 'tanggal_akhir'] as $dateField) {
                if (isset($inpDate[$dateField]) && $inpDate[$dateField] !== null) {
                    $date = new DateTime($inpDate[$dateField]);
                    $monthNumber = $date->format('m');
                    $indonesianMonth = $monthTranslations[$monthNumber];
                    $formattedDate = $date->format('d') . ' ' . $indonesianMonth . ' ' . $date->format('Y');
                    $inpDate[$dateField] = $formattedDate;
                }
            }
        } else {
            $processedData = [];
            foreach ($inpDate as $inpDateRow) {
                $processedRow = $inpDateRow;
                foreach (['tanggal', 'tanggal_awal', 'tanggal_akhir'] as $dateField) {
                    if (isset($processedRow[$dateField]) && $processedRow[$dateField] !== null) {
                        $date = new DateTime($processedRow[$dateField]);
                        $monthNumber = $date->format('m');
                        $indonesianMonth = $monthTranslations[$monthNumber];
                        $formattedDate = $date->format('d') . ' ' . $indonesianMonth . ' ' . $date->format('Y');
                        $processedRow[$dateField] = $formattedDate;
                    }
                }
                $processedData[] = $processedRow;
            }
            $inpDate = $processedData;
        }
        return $inpDate;
    }
    public function showSewa(Request $request){
        $totalTempat = ListTempat::count();
        $totalPengajuan = SewaTempat::where('status', 'diajukan')->orWhere('status', 'proses')->count();
        $totalRiwayat = SewaTempat::where('status', 'diterima')->orWhere('status', 'ditolak')->count();
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'totalTempat'=>$totalTempat,
            'totalPengajuan'=>$totalPengajuan,
            'totalRiwayat'=>$totalRiwayat,
        ];
        return view('page.sewa.sewa',$dataShow);
    }
    public function showFormulir(Request $request){
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
        ];
        return view('page.sewa.formulir',$dataShow);
    }
    public function showPengajuan(Request $request){
        $sewaData = $this->changeMonth(SewaTempat::select('id_sewa', 'nama_peminjam', 'nama_tempat', DB::raw('DATE(created_at) AS tanggal'), 'status')
        ->where(function ($query) {
            $query->where('status', 'diajukan')->orWhere('status', 'proses');
        })->orderBy('id_sewa', 'DESC')->get());
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'sewaData'=>$sewaData,
        ];
        return view('page.sewa.pengajuan',$dataShow);
    }
    public function showRiwayat(Request $request){
        $sewaData = $this->changeMonth(SewaTempat::select('id_sewa', 'nama_peminjam', 'nama_tempat', DB::raw('DATE(created_at) AS tanggal'), 'status')
        ->where(function ($query) {
            $query->where('status', 'diterima')->orWhere('status', 'ditolak');
        })->orderBy('id_sewa', 'DESC')->get());
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'sewaData'=>$sewaData,
        ];
        return view('page.sewa.riwayat',$dataShow);
    }
    public function showDetail(Request $request, $sewaId){
        $sewaData = $this->changeMonth(SewaTempat::select(
            'id_sewa',
            'nik_sewa',
            'nama_peminjam',
            'nama_tempat',
            'deskripsi_sewa_tempat',
            'nama_kegiatan_sewa',
            'jumlah_peserta',
            'instansi',
            DB::raw('DATE(tgl_awal_peminjaman) AS tanggal_awal'),
            DB::raw('DATE_FORMAT(tgl_awal_peminjaman, "%H:%i") AS waktu_awal'),
            'kode_pinjam',
            DB::raw('DATE(tgl_akhir_peminjaman) AS tanggal_akhir'),
            DB::raw('DATE_FORMAT(tgl_akhir_peminjaman, "%H:%i") AS waktu_akhir'),
            'status',
            'catatan'
        )->where('id_sewa', $sewaId)->limit(1)->get()[0]);
        $sewaData['nik_sewa'] = Crypt::decrypt($sewaData['nik_sewa']);
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'sewaData'=>$sewaData,
        ];
        return view('page.sewa.detail',$dataShow);
    }
}