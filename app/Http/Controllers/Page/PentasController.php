<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\SuratAdvis;
use DateTime;
class PentasController extends Controller
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
    public function getPentasPengajuan(Request $request){
        $validator = Validator::make($request->only('tanggal'), [
            'tanggal'=>'required',
        ], [
            'tanggal.required' => 'Tanggal wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        if($request->input('tanggal') === 'semua'){
            $pentas = SuratAdvis::select('id_advis', 'nomor_induk', 'nama_advis', DB::raw('DATE(created_at) AS tanggal'), 'status')->where(function ($query) {
                $query->where('status', 'diajukan')->orWhere('status', 'proses');
            })->orderBy('id_advis','DESC')->get();
        }else{
            $tanggal = explode('-', $request->input('tanggal')); 
            $pentas = SuratAdvis::select('id_advis', 'nomor_induk', 'nama_advis', DB::raw('DATE(created_at) AS tanggal'), 'status')->where(function ($query) {
                $query->where('status', 'diajukan')->orWhere('status', 'proses');
            })->whereRaw('MONTH(updated_at) = ?',[$tanggal[0]])->whereRaw('YEAR(updated_at) = ?',[$tanggal[1]])->orderBy('id_advis','DESC')->get();
        }
        if (!$pentas) {
            return response()->json(['status' => 'error', 'message' => 'Data surat advis tidak ditemukan'], 400);
        }
        return response()->json(['status'=>'success','data'=>$this->changeMonth($pentas)]);
    }
    public function getPentasRiwayat(Request $request){
        $validator = Validator::make($request->only('tanggal'), [
            'tanggal'=>'required',
        ], [
            'tanggal.required' => 'Tanggal wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        if($request->input('tanggal') === 'semua'){
            $pentas = SuratAdvis::select('id_advis', 'nomor_induk', 'nama_advis', DB::raw('DATE(created_at) AS tanggal'), 'status')->where(function ($query) {
                $query->where('status', 'ditolak')->orWhere('status', 'diterima');
            })->orderBy('id_advis','DESC')->get();
        }else{
            $tanggal = explode('-', $request->input('tanggal'));
            $pentas = SuratAdvis::select('id_advis', 'nomor_induk', 'nama_advis', DB::raw('DATE(created_at) AS tanggal'), 'status')->where(function ($query) {
                $query->where('status', 'ditolak')->orWhere('status', 'diterima');
            })->whereRaw('MONTH(updated_at) = ?',[$tanggal[0]])->whereRaw('YEAR(updated_at) = ?',[$tanggal[1]])->orderBy('id_advis','DESC')->get();
        }
        if (!$pentas) {
            return response()->json(['status' => 'error', 'message' => 'Data surat advis tidak ditemukan'], 400);
        }
        return response()->json(['status'=>'success','data'=>$this->changeMonth($pentas)]);
    }
    public function showPentas(Request $request){
        $totalPengajuan = SuratAdvis::where('status', 'diajukan')->orWhere('status', 'proses')->count();
        $totalRiwayat = SuratAdvis::where('status', 'diterima')->orWhere('status', 'ditolak')->count();
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'totalPengajuan'=>$totalPengajuan,
            'totalRiwayat'=>$totalRiwayat,
        ];
        return view('page.pentas.pentas',$dataShow);
    }
    public function showFormulir(Request $request){
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
        ];
        return view('page.pentas.formulir',$dataShow);
    }
    public function showPengajuan(Request $request){
        $pentasData = $this->changeMonth(SuratAdvis::select('id_advis', 'nomor_induk', 'nama_advis', DB::raw('DATE(created_at) AS tanggal'), 'status')
        ->where(function ($query) {
            $query->where('status', 'diajukan')->orWhere('status', 'proses');
        })->orderBy('id_advis', 'DESC')->get());
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'pentasData'=>$pentasData,
        ];
        return view('page.pentas.pengajuan',$dataShow);
    }
    public function showRiwayat(Request $request){
        $pentasData = $this->changeMonth(SuratAdvis::select('id_advis', 'nomor_induk', 'nama_advis', DB::raw('DATE(created_at) AS tanggal'), 'status', 'kode_verifikasi')
        ->where(function ($query) {
            $query->where('status', 'diterima')->orWhere('status', 'ditolak');
        })->orderBy('id_advis', 'DESC')->get());
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'pentasData'=>$pentasData,
        ];
        return view('page.pentas.riwayat',$dataShow);
    }
    public function showDetail(Request $request, $pentasId){
        $pentasData = $this->changeMonth(SuratAdvis::select(
            'id_advis',
            'nomor_induk',
            'nama_advis',
            'alamat_advis',
            'deskripsi_advis',
            'kode_verifikasi',
            DB::raw('DATE(tgl_advis) AS tanggal'),
            'tempat_advis',
            'status',
            'catatan',
        )->where('id_advis', '=', $pentasId)->limit(1)->get()[0]);
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'pentasData'=>$pentasData,
        ];
        return view('page.pentas.detail',$dataShow);
    }
}