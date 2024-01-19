<?php
namespace App\Http\Controllers\Mobile\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\KategoriSeniman;
use App\Models\Seniman;
use Carbon\Carbon;
class SenimanController extends Controller
{
    public function pengajuanSeniman(Request $request){
        $validator = Validator::make($request->only('nama_seniman', 'nik', 'alamat_seniman', 'no_telpon', 'jenis_kelamin', 'kategori', 'kecamatan', 'tempat_lahir', 'tanggal_lahir', 'nama_organisasi', 'jumlah_anggota', 'ktp_seniman', 'pass_foto', 'surat_keterangan'), [
            'nama_seniman' => 'required|max:50',
            'nik' => 'required|numeric|digits_between:10,30',
            'alamat_seniman' => 'required',
            'no_telpon' => 'required',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kategori' => 'required',
            'kecamatan' => 'required|in:bagor,baron,berbek,gondang,jatikalen,kertosono,lengkong,loceret,nganjuk,ngetos,ngluyu,ngronggot,pace,patianrowo,prambon,rejoso,sawahan,sukomoro,tanjunganom,wilangan',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'nama_organisasi' => 'nullable',
            'jumlah_anggota' => 'nullable|numeric|digits_between:0,5',
            'ktp_seniman' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'pass_foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'surat_keterangan' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ], [
            'nama_seniman.required' => 'Nama seniman wajib di isi',
            'nama_seniman.max' => 'Nama seniman maksimal 50 karakter',
            'nik.required' => 'NIK wajib di isi',
            'nik.numeric' => 'NIK harus berupa angka',
            'nik.digits_between' => 'NIK maksimal 30 digit',
            'alamat_seniman.required' => 'Alamat seniman wajib di isi',
            'no_telpon.required' => 'Nomor telepon wajib di isi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib di isi',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
            'kategori.required' => 'Kategori wajib di isi',
            'kecamatan.required' => 'Kecamatan wajib di isi',
            'kecamatan.in' => 'Kecamatan tidak valid',
            'tempat_lahir.required' => 'Tempat lahir wajib di isi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib di isi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'nama_organisasi.required' => 'Nama organisasi wajib di isi',
            'jumlah_anggota.required' => 'Jumlah anggota wajib di isi',
            'jumlah_anggota.numeric' => 'Jumlah anggota harus berupa angka',
            'jumlah_anggota.digits_between' => 'Jumlah anggota maksimal 5 digit',
            'ktp_seniman.required' => 'KTP seniman wajib di isi',
            'ktp_seniman.image' => 'KTP seniman harus berupa gambar',
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
        $seniman = Seniman::select('nama_tempat')->find($request->input('id_tempat'));
        if(!$seniman){
            return response()->json(['status' => 'error', 'message' => 'Data seniman tidak ditemukan'], 404);
        }
        //get user
        $idUser = User::select("id_user")->whereRaw("BINARY email = ?",[$request->input('email')])->limit(1)->get()[0]['id_user'];

        //process file foto ktp
        if (!$request->hasFile('ktp_seniman')) {
            return response()->json(['status'=>'error','message'=>'Foto KTP wajib di isi'], 400);
        }
        $file = $request->file('ktp_seniman');
        if(!($file->isValid() && in_array($file->extension(), ['pdf', 'jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Foto KTP tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $fileName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('seniman')->put('ktp_seniman/' . $fileName, $fileData);

        //process file pass foto
        if (!$request->hasFile('pass_foto')) {
            return response()->json(['status'=>'error','message'=>'Pass Foto wajib di isi'], 400);
        }
        $file = $request->file('pass_foto');
        if(!($file->isValid() && in_array($file->extension(), ['pdf', 'jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Pass foto tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $fileName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('seniman')->put('pass_foto/' . $fileName, $fileData);

        //process file surat keterangan
        if (!$request->hasFile('surat_keterangan')) {
            return response()->json(['status'=>'error','message'=>'Surat keterangan wajib di isi'], 400);
        }
        $file = $request->file('surat_keterangan');
        if(!($file->isValid() && in_array($file->extension(), ['pdf', 'jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Surat Keterangan tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $fileName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('seniman')->put('surat/' . $fileName, $fileData);
        //add seniman
        $tambahSeniman = Seniman::create([
            'nama_seniman' => $request->input('nama_seniman'),
            'nik' => $request->input('nik'),
            'alamat_seniman' => $request->input('alamat_seniman'),
            'no_telpon' => $request->input('no_telpon'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'kategori' => $request->input('kategori'),
            'kecamatan' => $request->input('kecamatan'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => Carbon::createFromFormat('d-m-Y', $request->input('tanggal_lahir'))->format('Y-m-d'),
            'nama_organisasi' => $request->input('nama_organisasi'),
            'jumlah_anggota' => $request->input('jumlah_anggota'),
            'ktp_seniman' => $request->input('ktp_seniman'),
            'pass_foto' => $request->input('pass_foto'),
            'surat_keterangan' => $request->input('surat_keterangan'),
            'status' => 'diajukan',
            'catatan' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'id_user' => $idUser,
        ]);
        if (!$tambahSeniman) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan data Seniman'], 500);
        }
        return response()->json(['status'=>'success','message'=>'Data Seniman berhasil ditambahkan']);
    }
    public function editSeniman(Request $request){
        $validator = Validator::make($request->only('nama_seniman', 'nik', 'alamat_seniman', 'no_telpon', 'jenis_kelamin', 'kategori', 'kecamatan', 'tempat_lahir', 'tanggal_lahir', 'nama_organisasi', 'jumlah_anggota', 'ktp_seniman', 'pass_foto', 'surat_keterangan'), [
            'nama_seniman' => 'required|max:50',
            'nik' => 'required|numeric|digits_between:10,30',
            'alamat_seniman' => 'required',
            'no_telpon' => 'required',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kategori' => 'required',
            'kecamatan' => 'required|in:bagor,baron,berbek,gondang,jatikalen,kertosono,lengkong,loceret,nganjuk,ngetos,ngluyu,ngronggot,pace,patianrowo,prambon,rejoso,sawahan,sukomoro,tanjunganom,wilangan',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'nama_organisasi' => 'nullable',
            'jumlah_anggota' => 'nullable|numeric|digits_between:0,5',
            'ktp_seniman' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'pass_foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'surat_keterangan' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ], [
            'nama_seniman.required' => 'Nama seniman wajib di isi',
            'nama_seniman.max' => 'Nama seniman maksimal 50 karakter',
            'nik.required' => 'NIK wajib di isi',
            'nik.numeric' => 'NIK harus berupa angka',
            'nik.digits_between' => 'NIK maksimal 30 digit',
            'alamat_seniman.required' => 'Alamat seniman wajib di isi',
            'no_telpon.required' => 'Nomor telepon wajib di isi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib di isi',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
            'kategori.required' => 'Kategori wajib di isi',
            'kecamatan.required' => 'Kecamatan wajib di isi',
            'kecamatan.in' => 'Kecamatan tidak valid',
            'tempat_lahir.required' => 'Tempat lahir wajib di isi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib di isi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'nama_organisasi.required' => 'Nama organisasi wajib di isi',
            'jumlah_anggota.required' => 'Jumlah anggota wajib di isi',
            'jumlah_anggota.numeric' => 'Jumlah anggota harus berupa angka',
            'jumlah_anggota.digits_between' => 'Jumlah anggota maksimal 5 digit',
            'ktp_seniman.required' => 'KTP seniman wajib di isi',
            'ktp_seniman.image' => 'KTP seniman harus berupa gambar',
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
        //check data seniman
        $seniman = Seniman::select('seniman.id_user', 'status', 'ktp_seniman', 'pass_foto', 'surat_ket_sewa')->where('users.email', $request->input('email'))->where('seniman.id_seniman', $request->input('id_seniman'))->join('users', 'seniman.id_user', '=', 'users.id_user')->first();
        if (!$seniman) {
            return response()->json(['status' => 'error', 'message' => 'Data Seniman tidak ditemukan'], 404);
        }
        //check status
        if($seniman->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data Seniman sedang diproses'], 400);
        }else if($seniman->status == 'diterima' || $seniman->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data Seniman sudah diverifikasi'], 400);
        }

        //process file ktp seniman
        if (!$request->hasFile('ktp_seniman')) {
            return response()->json(['status'=>'error','message'=>'Foto KTP wajib di isi'], 400);
        }
        $file = $request->file('ktp_seniman');
        if(!($file->isValid() && in_array($file->extension(), ['jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Foto KTP tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $destinationPath = storage_path('app/seniman/');
        $fileToDelete = $destinationPath . $seniman->surat_ket_sewa;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('seniman')->delete('/'.$seniman->surat_ket_sewa);
        $fileName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('seniman')->put('/' . $fileName, $fileData);

        //process file pass foto
        if (!$request->hasFile('pass_foto')) {
            return response()->json(['status'=>'error','message'=>'Pass Foto wajib di isi'], 400);
        }
        $file = $request->file('pass_foto');
        if(!($file->isValid() && in_array($file->extension(), ['jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Pass Foto tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $destinationPath = storage_path('app/seniman/');
        $fileToDelete = $destinationPath . $seniman->surat_ket_sewa;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('seniman')->delete('/'.$seniman->surat_ket_sewa);
        $fileName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('seniman')->put('/' . $fileName, $fileData);

        //process file surat keterangan
        if (!$request->hasFile('surat_keterangan')) {
            return response()->json(['status'=>'error','message'=>'Surat keterangan wajib di isi'], 400);
        }
        $file = $request->file('surat_keterangan');
        if(!($file->isValid() && in_array($file->extension(), ['jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Surat Keterangan tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $destinationPath = storage_path('app/seniman/');
        $fileToDelete = $destinationPath . $seniman->surat_ket_sewa;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('seniman')->delete('/'.$seniman->surat_ket_sewa);
        $fileName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('seniman')->put('/' . $fileName, $fileData);

        //update seniman
        $updatedSeniman = Seniman::where('id_seniman',$request->input('id_seniman'))->update([
            'nik_sewa' => $request->input('nik_penyewa'),
            'nama_peminjam' => $request->input('nama_peminjam'),
            'deskripsi_sewa_tempat' => $request->input('deskripsi'),
            'nama_kegiatan_sewa' => $request->input('nama_kegiatan_sewa'),
            'instansi' => (is_null($request->input('instansi')) || empty($request->input('instansi'))) ? '' : $request->input('instansi'),
            'jumlah_peserta' => (is_null($request->input('jumlah_peserta')) || empty($request->input('jumlah_peserta'))) ? 0 : $request->input('jumlah_peserta'),
            'tgl_awal_peminjaman' => Carbon::createFromFormat('d-m-Y', $request->input('tanggal_awal_sewa'))->format('Y-m-d'),
            'tgl_akhir_peminjaman' => Carbon::createFromFormat('d-m-Y', $request->input('tanggal_akhir_sewa'))->format('Y-m-d'),
            'surat_ket_sewa' => $fileName,
            'status' => (is_null($seniman->status) || empty($seniman->status)) ? 'diajukan' : $seniman->status,
            'id_tempat'=>$request->input('id_tempat'),
            'updated_at' => Carbon::now(),
        ]);
        if (!$updatedSeniman) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui data Seniman'], 500);
        }
        return response()->json(['status' =>'success','message'=>'Data Seniman berhasil di perbarui']);
    }
    public function hapusSeniman(Request $request){
        $validator = Validator::make($request->only('id_seniman'), [
            'id_seniman' => 'required',
        ], [
            'id_seniman.required' => 'ID Seniman wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => $errors], 400);
        }
        //check data seniman
        $seniman = Seniman::select('seniman.id_user', 'status','surat_ket_sewa')->where('users.email', $request->input('email'))->where('seniman.id_seniman', $request->input('id_seniman'))->join('users', 'seniman.id_user', '=', 'users.id_user')->first();
        if (!$seniman) {
            return response()->json(['status' => 'error', 'message' => 'Data Seniman tidak ditemukan'], 404);
        }
        //check status
        if($seniman->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data Seniman sedang diproses'], 400);
        }else if($seniman->status == 'diterima' || $seniman->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data Seniman sudah diverifikasi'], 400);
        }
        $destinationPath = storage_path('app/seniman/');
        $fileToDelete = $destinationPath . $seniman->surat_ket_sewa;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('seniman')->delete('/'.$seniman->surat_ket_sewa);
        if (!Seniman::where('id_seniman',$request->input('id_seniman'))->delete()) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data Seniman'], 500);
        }
        return response()->json(['status' => 'success', 'message' => 'Data Seniman berhasil dihapus']);
    }
    public function tambahPerpanjangan(Request $request){
        //
    }
    public function hapusPerpanjangan(Request $request){
        $validator = Validator::make($request->only('id_seniman'), [
            'id_seniman' => 'required',
        ], [
            'id_seniman.required' => 'ID Seniman wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => $errors], 400);
        }
        //check data seniman
        $seniman = Seniman::select('seniman.id_user', 'status','surat_ket_sewa')->where('users.email', $request->input('email'))->where('seniman.id_seniman', $request->input('id_seniman'))->join('users', 'seniman.id_user', '=', 'users.id_user')->first();
        if (!$seniman) {
            return response()->json(['status' => 'error', 'message' => 'Data Seniman tidak ditemukan'], 404);
        }
        //check status
        if($seniman->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data Seniman sedang diproses'], 400);
        }else if($seniman->status == 'diterima' || $seniman->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data Seniman sudah diverifikasi'], 400);
        }
        $destinationPath = storage_path('app/seniman/');
        $fileToDelete = $destinationPath . $seniman->surat_ket_sewa;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('seniman')->delete('/'.$seniman->surat_ket_sewa);
        if (!Seniman::where('id_seniman',$request->input('id_seniman'))->delete()) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data Seniman'], 500);
        }
        return response()->json(['status' => 'success', 'message' => 'Data Seniman berhasil dihapus']);
    }
}