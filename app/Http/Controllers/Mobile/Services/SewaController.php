<?php
namespace App\Http\Controllers\Mobile\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ListTempat;
use App\Models\SewaTempat;
use Carbon\Carbon;
class SewaController extends Controller
{
    public function pengajuanSewa(Request $request){
        $validator = Validator::make($request->only('id_tempat', 'nik_penyewa', 'nama_peminjam', 'deskripsi', 'nama_kegiatan_sewa', 'instansi', 'jumlah_peserta', 'tanggal_awal_sewa', 'tanggal_akhir_sewa', 'surat_keterangan'), [
            'id_tempat' => 'required',
            'nik_penyewa' => 'required|numeric|digits_between:10,30',
            'nama_peminjam' => 'required|max:30',
            'deskripsi' => 'required|max:100',
            'nama_kegiatan_sewa' => 'required|min:6|max:50',
            'instansi' => 'nullable|max:50',
            'jumlah_peserta' => 'required|numeric|digits_between:0,5',
            'tanggal_awal_sewa' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'tanggal_akhir_sewa' => ['required', 'date', 'after_or_equal:tanggal_awal_sewa', 'after_or_equal:' . now()->toDateString()],
            'surat_keterangan' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ], [
            'id_tempat.required' => 'ID Tempat wajib di isi',
            'nik_penyewa.required' => 'NIK penyewa wajib di isi',
            'nik_penyewa.numeric' => 'NIK penyewa harus berupa angka',
            'nik_penyewa.digits_between' => 'NIK penyewa maksimal 30 digit',
            'nama_peminjam.required' => 'Nama peminjam wajib di isi',
            'nama_peminjam.max' => 'Nama peminjam maksimal 30 karakter',
            'deskripsi.required' => 'Deskripsi wajib di isi',
            'deskripsi.max' => 'Deskripsi maksimal 100 karakter',
            'nama_kegiatan_sewa.required' => 'Nama kegiatan sewa wajib di isi',
            'nama_kegiatan_sewa.min' => 'Nama kegiatan sewa minimal 6 karakter',
            'nama_kegiatan_sewa.max' => 'Nama kegiatan sewa maksimal 50 karakter',
            'instansi.max' => 'Instansi maksimal 50 karakter',
            'jumlah_peserta.required' => 'Jumlah peserta wajib di isi',
            'jumlah_peserta.numeric' => 'Jumlah peserta harus berupa angka',
            'jumlah_peserta.digits_between' => 'Jumlah peserta maksimal 30 digit',
            'tanggal_awal_sewa.required' => 'Tanggal awal sewa wajib di isi',
            'tanggal_awal_sewa.date' => 'Format tanggal awal sewa tidak valid',
            'tanggal_awal_sewa.after_or_equal' => 'Tanggal awal sewa harus setelah atau sama dengan tanggal sekarang',
            'tanggal_akhir_sewa.required' => 'Tanggal akhir sewa wajib di isi',
            'tanggal_akhir_sewa.date' => 'Format tanggal akhir sewa tidak valid',
            'tanggal_akhir_sewa.after_or_equal' => 'Tanggal akhir sewa harus setelah atau sama dengan tanggal awal sewa',
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
        //check tempat
        $tempat = ListTempat::select('nama_tempat')->find($request->input('id_tempat'));
        if(!$tempat){
            return response()->json(['status' => 'error', 'message' => 'Data tempat tidak ditemukan'], 404);
        }
        //get user
        $idUser = User::select("id_user")->whereRaw("BINARY email = ?",[$request->input('email')])->limit(1)->get()[0]['id_user'];
        //process file
        if (!$request->hasFile('surat_keterangan')) {
            return response()->json(['status'=>'error','message'=>'Surat keterangan wajib di isi'], 400);
        }
        $file = $request->file('surat_keterangan');
        if(!($file->isValid() && in_array($file->extension(), ['pdf', 'jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Surat Keterangan tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $fileName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('sewa')->put('/' . $fileName, $fileData);
        //add sewa
        $tambahSewa = SewaTempat::create([
            'nama_tempat' => $tempat->nama_tempat,
            'nama_pengirim' => $request->input('nama_pengirim'),
            'nik_sewa' => $request->input('nik_penyewa'),
            'nama_peminjam' => $request->input('nama_peminjam'),
            'deskripsi_sewa_tempat' => $request->input('deskripsi'),
            'nama_kegiatan_sewa' => $request->input('nama_kegiatan_sewa'),
            'instansi' => (is_null($request->input('instansi')) || empty($request->input('instansi'))) ? '' : $request->input('instansi'),
            'jumlah_peserta' => (is_null($request->input('jumlah_peserta')) || empty($request->input('jumlah_peserta'))) ? 0 : $request->input('jumlah_peserta'),
            'tgl_awal_peminjaman' => Carbon::createFromFormat('d-m-Y', $request->input('tanggal_awal_sewa'))->format('Y-m-d'),
            'tgl_akhir_peminjaman' => Carbon::createFromFormat('d-m-Y', $request->input('tanggal_akhir_sewa'))->format('Y-m-d'),
            'surat_ket_sewa' => $fileName,
            'status' => 'diajukan',
            'catatan' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'id_user' => $idUser,
            'id_tempat' => $request->input('id_tempat'),
        ]);
        if (!$tambahSewa) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan data Sewa Tempat'], 500);
        }
        return response()->json(['status'=>'success','message'=>'Data Sewa tempat berhasil ditambahkan']);
    }
    public function editSewa(Request $request){
        $validator = Validator::make($request->only('id_sewa', 'id_tempat', 'nik_penyewa', 'nama_peminjam', 'deskripsi', 'nama_kegiatan_sewa', 'instansi', 'jumlah_peserta', 'tanggal_awal_sewa', 'tanggal_akhir_sewa', 'surat_keterangan'), [
            'id_sewa' => 'required',
            'id_tempat' => 'required',
            'nik_penyewa' => 'required|numeric|digits_between:10,30',
            'nama_peminjam' => 'required|min:6|max:30',
            'deskripsi' => 'required|max:100',
            'nama_kegiatan_sewa' => 'required|min:6|max:50',
            'instansi' => 'nullable|max:50',
            'jumlah_peserta' => 'required|numeric|digits_between:0,5',
            'tanggal_awal_sewa' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'tanggal_akhir_sewa' => ['required', 'date', 'after_or_equal:tanggal_awal_sewa', 'after_or_equal:' . now()->toDateString()],
            'surat_keterangan' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ], [
            'id_sewa.required' => 'ID Sewa wajib di isi',
            'id_tempat.required' => 'ID Tempat wajib di isi',
            'nik_penyewa.required' => 'NIK penyewa wajib di isi',
            'nik_penyewa.numeric' => 'NIK penyewa harus berupa angka',
            'nik_penyewa.max' => 'NIK penyewa maksimal 30 digit',
            'nama_peminjam.required' => 'Nama peminjam wajib di isi',
            'nama_peminjam.min' => 'Nama peminjam minimal 6 karakter',
            'nama_peminjam.max' => 'Nama peminjam maksimal 30 karakter',
            'deskripsi.required' => 'Deskripsi wajib di isi',
            'deskripsi.max' => 'Deskripsi maksimal 100 karakter',
            'nama_kegiatan_sewa.required' => 'Nama kegiatan sewa wajib di isi',
            'nama_kegiatan_sewa.min' => 'Nama kegiatan sewa minimal 6 karakter',
            'nama_kegiatan_sewa.max' => 'Nama kegiatan sewa maksimal 50 karakter',
            'instansi.max' => 'Instansi maksimal 50 karakter',
            'jumlah_peserta.required' => 'Jumlah peserta wajib di isi',
            'jumlah_peserta.numeric' => 'Jumlah peserta harus berupa angka',
            'jumlah_peserta.digits_between' => 'Jumlah peserta maksimal 30 digit',
            'tanggal_awal_sewa.required' => 'Tanggal awal sewa wajib di isi',
            'tanggal_awal_sewa.date' => 'Format tanggal awal sewa tidak valid',
            'tanggal_awal_sewa.after_or_equal' => 'Tanggal awal sewa harus setelah atau sama dengan tanggal sekarang',
            'tanggal_akhir_sewa.required' => 'Tanggal akhir sewa wajib di isi',
            'tanggal_akhir_sewa.date' => 'Format tanggal akhir sewa tidak valid',
            'tanggal_akhir_sewa.after_or_equal' => 'Tanggal akhir sewa harus setelah atau sama dengan tanggal awal sewa',
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
        //check tempat
        $tempat = ListTempat::select('nama_tempat')->find($request->input('id_tempat'));
        if(!$tempat){
            return response()->json(['status' => 'error', 'message' => 'Data tempat tidak ditemukan'], 404);
        }
        //check data sewa
        $sewa = SewaTempat::select('sewa_tempat.id_user', 'status','surat_ket_sewa')->where('users.email', $request->input('email'))->where('sewa_tempat.id_sewa', $request->input('id_sewa'))->join('users', 'sewa_tempat.id_user', '=', 'users.id_user')->first();
        if (!$sewa) {
            return response()->json(['status' => 'error', 'message' => 'Data Sewa tempat tidak ditemukan'], 404);
        }
        //check status
        if($sewa->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data sewa tempat sedang diproses'], 400);
        }else if($sewa->status == 'diterima' || $sewa->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data sewa tempat sudah diverifikasi'], 400);
        }
        //process file
        if (!$request->hasFile('surat_keterangan')) {
            return response()->json(['status'=>'error','message'=>'Surat keterangan wajib di isi'], 400);
        }
        $file = $request->file('surat_keterangan');
        if(!($file->isValid() && in_array($file->extension(), ['jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Surat Keterangan tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $destinationPath = storage_path('app/sewa/');
        $fileToDelete = $destinationPath . $sewa->surat_ket_sewa;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('sewa')->delete('/'.$sewa->surat_ket_sewa);
        $fileName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('sewa')->put('/' . $fileName, $fileData);
        //update sewa
        $updatedSewa = SewaTempat::where('id_sewa',$request->input('id_sewa'))->update([
            'nama_tempat' => $tempat->nama_tempat,
            'nik_sewa' => $request->input('nik_penyewa'),
            'nama_peminjam' => $request->input('nama_peminjam'),
            'deskripsi_sewa_tempat' => $request->input('deskripsi'),
            'nama_kegiatan_sewa' => $request->input('nama_kegiatan_sewa'),
            'instansi' => (is_null($request->input('instansi')) || empty($request->input('instansi'))) ? '' : $request->input('instansi'),
            'jumlah_peserta' => (is_null($request->input('jumlah_peserta')) || empty($request->input('jumlah_peserta'))) ? 0 : $request->input('jumlah_peserta'),
            'tgl_awal_peminjaman' => Carbon::createFromFormat('d-m-Y', $request->input('tanggal_awal_sewa'))->format('Y-m-d'),
            'tgl_akhir_peminjaman' => Carbon::createFromFormat('d-m-Y', $request->input('tanggal_akhir_sewa'))->format('Y-m-d'),
            'surat_ket_sewa' => $fileName,
            'status' => (is_null($sewa->status) || empty($sewa->status)) ? 'diajukan' : $sewa->status,
            'id_tempat'=>$request->input('id_tempat'),
            'updated_at' => Carbon::now(),
        ]);
        if (!$updatedSewa) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui data Sewa tempat'], 500);
        }
        return response()->json(['status' =>'success','message'=>'Data sewa tempat berhasil di perbarui']);
    }
    public function hapusSewa(Request $request){
        $validator = Validator::make($request->only('id_sewa'), [
            'id_sewa' => 'required',
        ], [
            'id_sewa.required' => 'ID Sewa tempat wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => $errors], 400);
        }
        //check data sewa
        $sewa = SewaTempat::select('sewa_tempat.id_user', 'status','surat_ket_sewa')->where('users.email', $request->input('email'))->where('sewa_tempat.id_sewa', $request->input('id_sewa'))->join('users', 'sewa_tempat.id_user', '=', 'users.id_user')->first();
        if (!$sewa) {
            return response()->json(['status' => 'error', 'message' => 'Data Sewa tempat tidak ditemukan'], 404);
        }
        //check status
        if($sewa->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data Sewa tempat sedang diproses'], 400);
        }else if($sewa->status == 'diterima' || $sewa->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data Sewa tempat sudah diverifikasi'], 400);
        }
        $destinationPath = storage_path('app/sewa/');
        $fileToDelete = $destinationPath . $sewa->surat_ket_sewa;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('sewa')->delete('/'.$sewa->surat_ket_sewa);
        if (!SewaTempat::where('id_sewa',$request->input('id_sewa'))->delete()) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data Sewa Tempat'], 500);
        }
        return response()->json(['status' => 'success', 'message' => 'Data Sewa Tempat berhasil dihapus']);
    }
}