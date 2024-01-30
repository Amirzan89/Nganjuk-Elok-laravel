<?php
namespace App\Http\Controllers\Services;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\SuratAdvis;
use Exception;
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
    public function prosesPentas(Request $request){
        try{
            $validator = Validator::make($request->only('id_pentas','keterangan','catatan'), [
                'id_pentas' => 'required',
                'keterangan' => 'required|in:proses,diterima,ditolak',
                'catatan' => 'nullable',
            ], [
                'id_pentas.required' => 'ID Pentas wajib di isi',
                'keterangan.required' => 'Keterangan wajib di isi',
                'keterangan.in' => 'Keterangan invalid',
            ]);
            if ($validator->fails()) {
                $errors = [];
                foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                    $errors[$field] = $errorMessages[0];
                    break;
                }
                return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
            }
            $ketInput = $request->input('keterangan');
            $catatanInput = $request->input('catatan');
            $event = SuratAdvis::select('status')->whereRaw("BINARY id_advis = ?",[$request->input('id_pentas')])->first();
            if (!$event) {
                return response()->json(['status' => 'error', 'message' => 'Surat Advis tidak ditemukan'], 400);
            }
            $statusDB = $event->status;

            //check status
            if($ketInput ==  'proses' && ($statusDB == 'diterima' || $statusDB == 'ditolak')){
                return response()->json(['status' => 'error', 'message' => 'Data sudah diverifikasi'], 400);
            }
            if($statusDB ==  'diajukan' && ($ketInput == 'diterima' || $ketInput == 'ditolak')){
                return response()->json(['status' => 'error', 'message' => 'Data harus di proses'], 400);
            }
            if($ketInput ==  'ditolak' && $statusDB == 'diterima'){
                return response()->json(['status' => 'error', 'message' => 'Data sudah diverifikasi'], 400);
            }
            if($ketInput ==  'diterima' && $statusDB == 'ditolak'){
                return response()->json(['status' => 'error', 'message' => 'Data sudah diverifikasi'], 400);
            }
            if($ketInput == 'ditolak' && (empty($catatanInput) && is_null($catatanInput))){
                return response()->json(['status' => 'error', 'message' => 'Catatan harus di isi !'], 400);
            }

            // Update the event using a raw query
            $updateQuery = SuratAdvis::whereRaw("BINARY id_advis = ?", [$request->input('id_pentas')])
            ->update([
                'status' => $ketInput == 'proses' ? 'proses' : ($ketInput == 'diterima' ? 'diterima' : 'ditolak'),
                'catatan' => ($ketInput == 'proses' || $ketInput == 'diterima') ? '' : $catatanInput,
            ]);
            if ($updateQuery <= 0) {
                return response()->json(['status' => 'error', 'message' => 'Status gagal diubah'], 500);
            }
            return response()->json(['status' => 'success', 'message' => 'Status berhasil diubah']);
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson->message,
                );
            }
            return response()->json($responseData,isset($errorJson['code']) ? $errorJson['code'] : 400);
        }
    }
}