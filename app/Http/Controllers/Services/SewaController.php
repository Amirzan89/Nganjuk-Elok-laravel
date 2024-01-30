<?php
namespace App\Http\Controllers\Services;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\SewaTempat;
use Exception;
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
    public function getSewaPengajuan(Request $request){
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
            $sewa = SewaTempat::select('id_sewa', 'nama_peminjam', 'nama_tempat', DB::raw('DATE(created_at) AS tanggal'), 'status')->where(function ($query) {
                $query->where('status', 'diajukan')->orWhere('status', 'proses');
            })->orderBy('id_sewa','DESC')->get();
        }else{
            $tanggal = explode('-', $request->input('tanggal')); 
            $sewa = SewaTempat::select('id_sewa', 'nama_peminjam', 'nama_tempat', DB::raw('DATE(created_at) AS tanggal'), 'status')->where(function ($query) {
                $query->where('status', 'diajukan')->orWhere('status', 'proses');
            })->whereRaw('MONTH(updated_at) = ?',[$tanggal[0]])->whereRaw('YEAR(updated_at) = ?',[$tanggal[1]])->orderBy('id_sewa','DESC')->get();
        }
        if (!$sewa) {
            return response()->json(['status' => 'error', 'message' => 'Data sewa tempat tidak ditemukan'], 400);
        }
        return response()->json(['status'=>'success','data'=>$this->changeMonth($sewa)]);
    }
    public function getSewaRiwayat(Request $request){
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
            $sewa = SewaTempat::select('id_sewa', 'nama_peminjam', 'nama_tempat', DB::raw('DATE(created_at) AS tanggal'), 'status')->where(function ($query) {
                $query->where('status', 'ditolak')->orWhere('status', 'diterima');
            })->orderBy('id_sewa','DESC')->get();
        }else{
            $tanggal = explode('-', $request->input('tanggal'));
            $sewa = SewaTempat::select('id_sewa', 'nama_peminjam', 'nama_tempat', DB::raw('DATE(created_at) AS tanggal'), 'status')->where(function ($query) {
                $query->where('status', 'ditolak')->orWhere('status', 'diterima');
            })->whereRaw('MONTH(updated_at) = ?',[$tanggal[0]])->whereRaw('YEAR(updated_at) = ?',[$tanggal[1]])->orderBy('id_sewa','DESC')->get();
        }
        if (!$sewa) {
            return response()->json(['status' => 'error', 'message' => 'Data sewa tempat tidak ditemukan'], 400);
        }
        return response()->json(['status'=>'success','data'=>$this->changeMonth($sewa)]);
    }
    public function prosesSewa(Request $request){
        try{
            $validator = Validator::make($request->only('id_sewa','keterangan','catatan'), [
                'id_sewa' => 'required',
                'keterangan' => 'required|in:proses,diterima,ditolak',
                'catatan' => 'nullable',
            ], [
                'id_sewa.required' => 'ID Sewa wajib di isi',
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
            $sewa = SewaTempat::select('status')->whereRaw("BINARY id_sewa = ?",[$request->input('id_sewa')])->first();
            if (!$sewa) {
                return response()->json(['status' => 'error', 'message' => 'Sewa tidak ditemukan'], 400);
            }
            $statusDB = $sewa->status;

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

            // Update the sewa using a raw query
            $updateQuery = SewaTempat::whereRaw("BINARY id_sewa = ?", [$request->input('id_sewa')])
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