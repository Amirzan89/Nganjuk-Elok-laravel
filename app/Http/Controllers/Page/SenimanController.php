<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\KategoriSeniman;
use App\Models\Seniman;
use App\Models\Perpanjangan;
use DateTime;
class SenimanController extends Controller
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
            echo 'single data';
            echo '<br>';
            foreach (['tanggal','tanggal_awal', 'tanggal_akhir'] as $dateField) {
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
    public function showSeniman(Request $request){
        $totalPengajuan = Seniman::where('status', 'diajukan')->orWhere('status', 'proses')->count();
        $totalPerpanjangan = Perpanjangan::where('status', 'diajukan')->orWhere('status', 'proses')->count();
        $totalRiwayat = Seniman::where('status', 'diterima')->orWhere('status', 'ditolak')->count();
        $totalSeniman = Seniman::where('status', 'diterima')->count();
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'totalPengajuan'=>$totalPengajuan,
            'totalPerpanjangan'=>$totalPerpanjangan,
            'totalSeniman'=>$totalSeniman,
            'totalRiwayat'=>$totalRiwayat,
        ];
        return view('page.seniman.seniman',$dataShow);
    }
    public function showFormulir(Request $request){
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
        ];
        return view('page.seniman.formulir',$dataShow);
    }
    public function showPengajuan(Request $request){
        $senimanData = $this->changeMonth(Seniman::select('id_seniman', 'nama_seniman', DB::raw('DATE(created_at) AS tanggal'), 'status')
        ->where(function ($query) {
            $query->where('status', 'diajukan')->orWhere('status', 'proses');
        })->orderBy('id_seniman', 'DESC')->get());
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'senimanData'=>$senimanData,
        ];
        return view('page.seniman.pengajuan',$dataShow);
    }
    public function showRiwayat(Request $request){
        $senimanData = $this->changeMonth(Seniman::select('id_seniman', 'nama_seniman', DB::raw('DATE(created_at) AS tanggal'), 'status')
        ->where(function ($query) {
            $query->where('status', 'diterima')->orWhere('status', 'ditolak');
        })->orderBy('id_seniman', 'DESC')->get());
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'senimanData'=>$senimanData,
        ];
        return view('page.seniman.riwayat',$dataShow);
    }
    public function showData(Request $request){
        $kategoriSeniman = KategoriSeniman::get();
        $senimanData = $this->changeMonth(Seniman::select('id_seniman', 'nomor_induk', 'nama_kategori', 'nama_seniman', 'no_telpon', DB::raw('DATE(created_at) AS tanggal'), 'status')->join('kategori_seniman', 'seniman.id_kategori_seniman', '=', 'kategori_seniman.id_kategori_seniman')
        ->where(function ($query) {
            $query->where('status', 'diterima');
        })->orderBy('id_seniman', 'DESC')->get());
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'senimanData'=>$senimanData,
            'kategoriSeniman'=>$kategoriSeniman,
        ];
        return view('page.seniman.data',$dataShow);
    }
    public function showDetailSeniman(Request $request, $senimanId){
        $senimanData = $this->changeMonth(Seniman::select(
            'id_seniman',
            'nik',
            'nomor_induk',
            'nama_seniman',
            'jenis_kelamin',
            'tempat_lahir',
            DB::raw('DATE(tanggal_lahir) AS tanggal'),
            'nama_kategori AS kategori',
            'alamat_seniman',
            'no_telpon',
            'nama_organisasi',
            'jumlah_anggota',
            'kecamatan',
            'status',
            'catatan',
            )->join('kategori_seniman', 'seniman.id_kategori_seniman', '=', 'kategori_seniman.id_kategori_seniman')->where('id_seniman', '=', $senimanId)->limit(1)->get()[0]);
            $dataShow = [
                'userAuth'=>$request->input('user_auth'),
                'senimanData'=>$senimanData,
            ];
            return view('page.seniman.detail',$dataShow);
        }
        public function showPerpanjangan(Request $request){
            $senimanData = $this->changeMonth(Perpanjangan::select('seniman.id_seniman AS id_seniman', 'id_perpanjangan', 'nama_seniman', DB::raw('DATE(perpanjangan.tgl_pembuatan) AS tanggal'), 'perpanjangan.status')->join('seniman', 'seniman.id_seniman', '=', 'perpanjangan.id_seniman')
            ->where(function ($query) {
                $query->where('perpanjangan.status', 'diajukan')->orWhere('perpanjangan.status', 'proses');
            })->orderBy('id_perpanjangan', 'DESC')->get());
            $dataShow = [
                'userAuth'=>$request->input('user_auth'),
                'senimanData'=>$senimanData,
            ];
            return view('page.seniman.pengajuan',$dataShow);
        }
    }