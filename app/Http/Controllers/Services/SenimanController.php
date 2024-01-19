<?php
namespace App\Http\Controllers\Services;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Seniman;
use App\Models\Perpanjangan;
use Exception;
class SenimanController extends Controller
{
    private static $constID = '411.302';
    private function generateNIS($data,$desc){
        try{
            if(!isset($data['id_kategori']) || empty($data['id_kategori'])){
                throw new Exception('ID Kategori harus di isi');
            }
            $kategoriData = $this->kategoriFile(['id_kategori_seniman'=>$data['id_kategori']],'get');
            //get last NIS
            date_default_timezone_set('Asia/Jakarta');
            if ($desc == 'diterima') {
                $total = Seniman::where('nomor_induk', 'like', '%/' . date('Y'))->where('id_kategori_seniman', $data['id_kategori'])->count();
            } elseif ($desc == 'perpanjangan') {
                $total = Seniman::where('nomor_induk', 'like', '%/' . (date('Y') + 1))->where('id_kategori_seniman', $data['id_kategori'])->count();
            } else {
                throw new Exception('Description invalid');
            }
            if(!$total){
                $total = 1;
            }else{
                $total++;
            }
            $total = str_pad($total, 3, '0', STR_PAD_LEFT);
            if($desc == 'diterima'){
                $nis = $kategoriData['singkatan_kategori'].'/'.$total.'/'.self::$constID.'/'.date('Y');
            }else if($desc == 'perpanjangan'){
                $nis = $kategoriData['singkatan_kategori'].'/'.$total.'/'.self::$constID.'/'.(date('Y')+1);
            }
            return ['nis'=>$nis,'kategori'=>$data['id_kategori']];
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
                    'message' => $errorJson['message'],
                );
            }
            isset($errorJson['code']) ? http_response_code($errorJson['code']) : http_response_code(400);
            echo json_encode($responseData);
            exit();
        }
    }
    public function prosesSeniman(Request $request){
        try{
            $validator = Validator::make($request->only('id_seniman','keterangan','catatan'), [
                'id_seniman' => 'required',
                'keterangan' => 'required|in:proses,diterima,ditolak',
                'catatan' => 'nullable',
            ], [
                'id_seniman.required' => 'ID Seniman wajib di isi',
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
            $seniman = Seniman::select('status')->whereRaw("BINARY id_seniman = ?",[$request->input('id_seniman')])->first();
            if (!$seniman) {
                return response()->json(['status' => 'error', 'message' => 'Seniman tidak ditemukan'], 400);
            }
            $statusDB = $seniman->status;

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

            // Update the event using a raw query
            $updateQuery = Seniman::whereRaw("BINARY id_seniman = ?", [$request->input('id_seniman')])
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
    public function prosesPerpanjangan(Request $request){
        try{
            $validator = Validator::make($request->only('id_perpanjangan','keterangan','catatan'), [
                'id_perpanjangan' => 'required',
                'keterangan' => 'required|in:proses,diterima,ditolak',
                'catatan' => 'nullable',
            ], [
                'id_perpanjangan.required' => 'ID Perpanjangan wajib di isi',
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
            $perpanjangan = Seniman::select('status')->whereRaw("BINARY id_perpanjangan = ?",[$request->input('id_perpanjangan')])->first();
            if (!$perpanjangan) {
                return response()->json(['status' => 'error', 'message' => 'Perpanjangan tidak ditemukan'], 400);
            }
            $statusDB = $perpanjangan->status;

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

            // Update the event using a raw query
            $updateQuery = Seniman::whereRaw("BINARY id_perpanjangan = ?", [$request->input('id_perpanjangan')])
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