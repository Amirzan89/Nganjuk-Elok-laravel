<?php
namespace App\Http\Controllers\Services;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\SuratAdvis;
use Exception;
class PentasController extends Controller
{
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
                }
                return response()->json(['status' => 'error', 'message' => $errors], 400);
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