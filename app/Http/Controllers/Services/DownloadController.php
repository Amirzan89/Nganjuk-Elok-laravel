<?php
namespace App\Http\Controllers\Services;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Seniman;
use App\Models\Perpanjangan;
use App\Models\Events;
use App\Models\SewaTempat;
class DownloadController extends Controller
{
    public function downloadEvent(Request $request){
        $validator = Validator::make($request->only('id_event','deskripsi'), [
            'id_event' => 'required',
            'deskripsi' => 'required|in:surat',
        ], [
            'id_event.required' => 'ID Event wajib di isi',
            'deskripsi.required' => 'deskripsi wajib di isi',
            'deskripsi.in' => 'deskripsi invalid',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $event = Events::select('nama_event','poster_event')->where('id_event',$request->input('id_event'))->join('detail_events', 'events.id_detail', '=', 'detail_events.id_detail')->limit(1)->get()[0];
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Data Event tidak ditemukan'], 404);
        }
        $filePath = storage_path("app/event/{$event->poster_event}");
        if (!file_exists($filePath)) {
            return response()->json(['status' => 'error', 'message' =>'Poster Event tidak ditemukan'], 404);
        }
        $tempFilePath = tempnam(sys_get_temp_dir(), 'decrypted_file');
        file_put_contents($tempFilePath, Crypt::decrypt(Storage::disk('event')->get("poster_event/{$event->poster_event}")));
        return Response::download($tempFilePath, $event->nama_event. '.' .pathinfo($event->poster_event, PATHINFO_EXTENSION));
    }
    public function downloadSewaTempat(Request $request){
        $validator = Validator::make($request->only('id_sewa','deskripsi'), [
            'id_sewa' => 'required',
            'deskripsi' => 'required|in:surat',
        ], [
            'id_sewa.required' => 'ID Sewa wajib di isi',
            'deskripsi.required' => 'deskripsi wajib di isi',
            'deskripsi.in' => 'deskripsi invalid',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $sewa = SewaTempat::select('nama_peminjam', 'surat_keterangan')->where('id_sewa',$request->input('id_sewa'))->limit(1)->get()[0];
        if (!$sewa) {
            return response()->json(['status' => 'error', 'message' => 'Data Sewa Tempat tidak ditemukan'], 404);
        }
        $filePath = storage_path("app/sewa/{$sewa->surat_keterangan}");
        if (!file_exists($filePath)) {
            return response()->json(['status' => 'error', 'message' => 'Surat Keterangan tidak ditemukan'], 404);
        }
        $tempFilePath = tempnam(sys_get_temp_dir(), 'decrypted_file');
        file_put_contents($tempFilePath, Crypt::decrypt(Storage::disk('sewa')->get("surat_keterangan/{$sewa->surat_keterangan}")));
        return Response::download($tempFilePath, $sewa->nama_peminjam. '.' .pathinfo($sewa->surat_keterangan, PATHINFO_EXTENSION));
    }
    public function downloadSeniman(Request $request){
        $validator = Validator::make($request->only('id_seniman','deskripsi'), [
            'id_seniman' => 'required',
            'deskripsi' => 'required|in:ktp,foto,surat',
        ], [
            'id_seniman.required' => 'ID seniman wajib di isi',
            'deskripsi.required' => 'deskripsi wajib di isi',
            'deskripsi.in' => 'deskripsi invalid',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $deskripsi = [
            'ktp'=>'ktp_seniman',
            'foto'=>'pass_foto',
            'surat'=>'surat_keterangan',
        ];
        if(!array_key_exists($request->input('deksripsi'),$deskripsi)){
            return response()->json(['status' => 'error', 'message' => 'Deskripsi invalid'], 400);
        }
        $field = $deskripsi[$request->input('deskripsi')];
        $seniman = Seniman::select('nama_seniman', $field)->where('id_seniman',$request->input('id_seniman'))->limit(1)->get()[0];
        if (!$seniman) {
            return response()->json(['status' => 'error', 'message' => 'Data Seniman tidak ditemukan'], 404);
        }
        $filePath = storage_path("app/seniman/{$field}/{$seniman->$field}");
        if (!file_exists($filePath)) {
            return response()->json(['status' => 'error', 'message' => ucfirst($field) . ' tidak ditemukan'], 404);
        }
        $tempFilePath = tempnam(sys_get_temp_dir(), 'decrypted_file');
        file_put_contents($tempFilePath, Crypt::decrypt(Storage::disk('seniman')->get("{$field}/{$seniman->$field}")));
        return Response::download($tempFilePath, $seniman->nama_seniman. '.' .pathinfo($seniman->$field, PATHINFO_EXTENSION));
    }
    public function downloadPerpanjangan(Request $request){
        $validator = Validator::make($request->only('id_perpanjangan','deskripsi'), [
            'id_perpanjangan' => 'required',
            'deskripsi' => 'required|in:ktp,foto,surat',
        ], [
            'id_perpanjangan.required' => 'ID Perpanjangan wajib di isi',
            'deskripsi.required' => 'deskripsi wajib di isi',
            'deskripsi.in' => 'deskripsi invalid',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $deskripsi = [
            'ktp'=>'ktp_seniman',
            'foto'=>'pass_foto',
            'surat'=>'surat_keterangan',
        ];
        if(!array_key_exists($request->input('deksripsi'),$deskripsi)){
            return response()->json(['status' => 'error', 'message' => 'Deskripsi invalid'], 400);
        }
        $field = $deskripsi[$request->input('deskripsi')];
        $perpanjangan = Perpanjangan::select('nama_seniman', $field)->where('id_perpanjangan',$request->input('id_perpanjangan'))->join('seniman', 'perpanjangan.id_seniman', '=', 'seniman.id_seniman')->limit(1)->get()[0];
        if (!$perpanjangan) {
            return response()->json(['status' => 'error', 'message' => 'Data Perpanjangan tidak ditemukan'], 404);
        }
        $filePath = storage_path("app/perpanjangan/{$field}/{$perpanjangan->$field}");
        if (!file_exists($filePath)) {
            return response()->json(['status' => 'error', 'message' => ucfirst($field) . ' tidak ditemukan'], 404);
        }
        $tempFilePath = tempnam(sys_get_temp_dir(), 'decrypted_file');
        file_put_contents($tempFilePath, Crypt::decrypt(Storage::disk('perpanjangan')->get("{$field}/{$perpanjangan->$field}")));
        return Response::download($tempFilePath,'perpanjangan ' . $perpanjangan->nama_seniman. '.' .pathinfo($perpanjangan->$field, PATHINFO_EXTENSION));
    }
}