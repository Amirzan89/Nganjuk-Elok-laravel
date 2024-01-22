<?php
namespace App\Http\Controllers\Mobile\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Seniman;
use App\Models\Perpanjangan;
use Carbon\Carbon;
class PerpanjanganController extends Controller
{
    public function tambahPerpanjangan(Request $request){
        $validator = Validator::make($request->only('id_seniman','nik', 'ktp_seniman', 'pass_foto', 'surat_keterangan'), [
            'id_seniman' => 'required',
            'nik' => 'required|numeric|digits_between:10,30',
            'ktp_seniman' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'pass_foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'surat_keterangan' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ], [
            'id_seniman.required' => 'ID Seniman wajib di isi',
            'nik.required' => 'NIK wajib di isi',
            'nik.numeric' => 'NIK harus berupa angka',
            'nik.digits_between' => 'NIK maksimal 30 digit',
            'ktp_seniman.required' => 'KTP perpanjangan wajib di isi',
            'ktp_seniman.image' => 'KTP perpanjangan harus berupa gambar',
            'ktp_seniman.mimes' => 'Format KTP tidak valid. Gunakan format jpeg, png, jpg',
            'ktp_seniman.max' => 'Ukuran KTP tidak boleh lebih dari 5MB',
            'pass_foto.required' => 'Foto wajib di isi',
            'pass_foto.image' => 'Foto harus berupa gambar',
            'pass_foto.mimes' => 'Format foto tidak valid. Gunakan format jpeg, png, jpg',
            'pass_foto.max' => 'Ukuran foto tidak boleh lebih dari 5MB',
            'surat_keterangan.required' => 'Surat keterangan wajib di isi',
            'surat_keterangan.file' => 'Surat keterangan harus berupa file',
            'surat_keterangan.mimes' => 'Format file tidak valid. Gunakan format pdf, jpeg, png, jpg',
            'surat_keterangan.max' => 'Ukuran file tidak boleh lebih dari 5MB',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check seniman
        $seniman = Seniman::select('seniman.id_user AS id_user', 'status')->where('users.email', $request->input('email'))->where('seniman.id_seniman', $request->input('id_seniman'))->join('users', 'seniman.id_user', '=', 'users.id_user')->first();
        if(!$seniman){
            return response()->json(['status' => 'error', 'message' => 'Data seniman tidak ditemukan'], 404);
        }
        //check status seniman
        if($seniman->status == 'diajukan' || $seniman->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data Seniman sedang diproses'], 400);
        }else if($seniman->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data Seniman ditolak silahkan mengajukan ulang'], 400);
        }

        //process file foto ktp
        if (!$request->hasFile('ktp_seniman')) {
            return response()->json(['status'=>'error','message'=>'Foto KTP wajib di isi'], 400);
        }
        $file = $request->file('ktp_seniman');
        if(!($file->isValid() && in_array($file->extension(), ['pdf', 'jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Foto KTP tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $ktpName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('perpanjangan')->put('ktp_seniman/' . $ktpName, $fileData);

        //process file pass foto
        if (!$request->hasFile('pass_foto')) {
            return response()->json(['status'=>'error','message'=>'Pass Foto wajib di isi'], 400);
        }
        $file = $request->file('pass_foto');
        if(!($file->isValid() && in_array($file->extension(), ['pdf', 'jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Pass foto tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $fotoName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('perpanjangan')->put('pass_foto/' . $fotoName, $fileData);

        //process file surat keterangan
        if (!$request->hasFile('surat_keterangan')) {
            return response()->json(['status'=>'error','message'=>'Surat keterangan wajib di isi'], 400);
        }
        $file = $request->file('surat_keterangan');
        if(!($file->isValid() && in_array($file->extension(), ['pdf', 'jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Surat Keterangan tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $suratName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('perpanjangan')->put('surat_keterangan/' . $suratName, $fileData);
        //add perpanjangan
        $tambahPerpanjangan = Perpanjangan::create([
            'nik' => Crypt::encrypt($request->input('nik')),
            'ktp_seniman' => $ktpName,
            'pass_foto' => $fotoName,
            'surat_keterangan' => $suratName,
            'status' => 'diajukan',
            'catatan' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'id_seniman'=> $request->input('id_seniman'),
            'id_user' => $seniman->id_user,
        ]);
        if (!$tambahPerpanjangan) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan data Perpanjangan'], 500);
        }
        return response()->json(['status'=>'success','message'=>'Data Perpanjangan berhasil ditambahkan']);
    }
    public function editPerpanjangan(Request $request){
        $validator = Validator::make($request->only('id_perpanjangan', 'nik', 'ktp_seniman', 'pass_foto', 'surat_keterangan'), [
            'id_perpanjangan' => 'required',
            'nik' => 'required|numeric|digits_between:10,30',
            'ktp_seniman' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'pass_foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'surat_keterangan' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ], [
            'id_perpanjangan.required' => 'ID perpanjangan wajib di isi',
            'nik.required' => 'NIK wajib di isi',
            'nik.numeric' => 'NIK harus berupa angka',
            'nik.digits_between' => 'NIK maksimal 30 digit',
            'ktp_seniman.required' => 'KTP perpanjangan wajib di isi',
            'ktp_seniman.image' => 'KTP perpanjangan harus berupa gambar',
            'ktp_seniman.mimes' => 'Format KTP tidak valid. Gunakan format jpeg, png, jpg',
            'ktp_seniman.max' => 'Ukuran KTP tidak boleh lebih dari 5MB',
            'pass_foto.required' => 'Foto wajib di isi',
            'pass_foto.image' => 'Foto harus berupa gambar',
            'pass_foto.mimes' => 'Format foto tidak valid. Gunakan format jpeg, png, jpg',
            'pass_foto.max' => 'Ukuran foto tidak boleh lebih dari 5MB',
            'surat_keterangan.required' => 'Surat keterangan wajib di isi',
            'surat_keterangan.file' => 'Surat keterangan harus berupa file',
            'surat_keterangan.mimes' => 'Format file tidak valid. Gunakan format pdf, jpeg, png, jpg',
            'surat_keterangan.max' => 'Ukuran file tidak boleh lebih dari 5MB',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check data perpanjangan
        $perpanjangan = Perpanjangan::select('perpanjangan.id_user', 'status', 'ktp_seniman', 'pass_foto', 'surat_keterangan')->where('users.email', $request->input('email'))->where('perpanjangan.id_perpanjangan', $request->input('id_perpanjangan'))->join('users', 'perpanjangan.id_user', '=', 'users.id_user')->first();
        if (!$perpanjangan) {
            return response()->json(['status' => 'error', 'message' => 'Data Perpanjangan tidak ditemukan'], 404);
        }
        //check status
        if($perpanjangan->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data Perpanjangan sedang diproses'], 400);
        }else if($perpanjangan->status == 'diterima' || $perpanjangan->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data Perpanjangan sudah diverifikasi'], 400);
        }

        //process file ktp perpanjangan
        if (!$request->hasFile('ktp_seniman')) {
            return response()->json(['status'=>'error','message'=>'Foto KTP wajib di isi'], 400);
        }
        $file = $request->file('ktp_seniman');
        if(!($file->isValid() && in_array($file->extension(), ['jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Foto KTP tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $destinationPath = storage_path('app/perpanjangan/');
        $fileToDelete = $destinationPath . $perpanjangan->ktp_seniman;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('perpanjangan')->delete('ktp_seniman/'.$perpanjangan->ktp_seniman);
        $ktpName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('perpanjangan')->put('ktp_seniman/' . $ktpName, $fileData);

        //process file pass foto
        if (!$request->hasFile('pass_foto')) {
            return response()->json(['status'=>'error','message'=>'Pass Foto wajib di isi'], 400);
        }
        $file = $request->file('pass_foto');
        if(!($file->isValid() && in_array($file->extension(), ['jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Pass Foto tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $destinationPath = storage_path('app/perpanjangan/');
        $fileToDelete = $destinationPath . $perpanjangan->pass_foto;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('perpanjangan')->delete('pass_foto/'.$perpanjangan->pass_foto);
        $fotoName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('perpanjangan')->put('pass_foto/' . $fotoName, $fileData);

        //process file surat keterangan
        if (!$request->hasFile('surat_keterangan')) {
            return response()->json(['status'=>'error','message'=>'Surat keterangan wajib di isi'], 400);
        }
        $file = $request->file('surat_keterangan');
        if(!($file->isValid() && in_array($file->extension(), ['pdf', 'jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Surat Keterangan tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $destinationPath = storage_path('app/perpanjangan/');
        $fileToDelete = $destinationPath . $perpanjangan->surat_keterangan;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('perpanjangan')->delete('surat_keterangan/'.$perpanjangan->surat_keterangan);
        $suratName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('perpanjangan')->put('surat_keterangan/' . $suratName, $fileData);

        //update perpanjangan
        $updatedPerpanjangan = Perpanjangan::where('id_perpanjangan',$request->input('id_perpanjangan'))->update([
            'nik' => Crypt::encrypt($request->input('nik')),
            'ktp_seniman' => $ktpName,
            'pass_foto' => $fotoName,
            'surat_keterangan' => $suratName,
            'status' => (is_null($perpanjangan->status) || empty($perpanjangan->status)) ? 'diajukan' : $perpanjangan->status,
            'updated_at' => Carbon::now(),
        ]);
        if (!$updatedPerpanjangan) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui data Perpanjangan'], 500);
        }
        return response()->json(['status' =>'success','message'=>'Data Perpanjangan berhasil di perbarui']);
    }
    public function hapusPerpanjangan(Request $request){
        $validator = Validator::make($request->only('id_perpanjangan'), [
            'id_perpanjangan' => 'required',
        ], [
            'id_perpanjangan.required' => 'ID Perpanjangan wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check data perpanjangan
        $perpanjangan = Perpanjangan::select('perpanjangan.id_user', 'status','ktp_seniman', 'pass_foto', 'surat_keterangan')->where('users.email', $request->input('email'))->where('perpanjangan.id_perpanjangan', $request->input('id_perpanjangan'))->join('users', 'perpanjangan.id_user', '=', 'users.id_user')->first();
        if (!$perpanjangan) {
            return response()->json(['status' => 'error', 'message' => 'Data Perpanjangan tidak ditemukan'], 404);
        }
        //check status
        if($perpanjangan->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data Perpanjangan sedang diproses'], 400);
        }else if($perpanjangan->status == 'diterima' || $perpanjangan->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data Perpanjangan sudah diverifikasi'], 400);
        }

        //delete file ktp
        $destinationPath = storage_path('app/perpanjangan/');
        $fileToDelete = $destinationPath . $perpanjangan->ktp_seniman;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('perpanjangan')->delete('ktp_seniman/'.$perpanjangan->ktp_seniman);

        //delete pass foto
        $destinationPath = storage_path('app/perpanjangan/');
        $fileToDelete = $destinationPath . $perpanjangan->pass_foto;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('perpanjangan')->delete('pass_foto/'.$perpanjangan->pass_foto);

        //delete surat keterangan
        $destinationPath = storage_path('app/perpanjangan/');
        $fileToDelete = $destinationPath . $perpanjangan->surat_keterangan;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('perpanjangan')->delete('surat_keterangan/'.$perpanjangan->surat_keterangan);

        if (!Perpanjangan::where('id_perpanjangan',$request->input('id_perpanjangan'))->delete()) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data Perpanjangan'], 500);
        }
        return response()->json(['status' => 'success', 'message' => 'Data Perpanjangan berhasil dihapus']);
    }
}