<?php
namespace App\Http\Controllers\Mobile\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Seniman;
use App\Models\SuratAdvis;
use Carbon\Carbon;
class PentasController extends Controller
{
    public function tambahPentas(Request $request){
        $validator = Validator::make($request->only('id_seniman', 'nama_advis', 'alamat_advis', 'deskripsi_advis', 'tgl_advis', 'tempat_advis'), [
            'id_seniman' => 'required',
            'nama_advis' => 'required|max:30',
            'alamat_advis' => 'required',
            'deskripsi_advis' => 'required|max:100',
            'tgl_advis' => ['required', 'date', 'after_or_equal:' . now()->addDays(7)->toDateString()],
            'tempat_advis' => 'required|max:30',
        ], [
            'id_seniman.required' => 'ID Seniman wajib di isi',
            'nama_advis.required' => 'Nama pentas wajib di isi',
            'nama_advis.max' => 'Nama pentas maksimal 30 karakter',
            'alamat_advis.required' => 'Alamat pentas wajib di isi',
            'alamat_advis.max' => 'Alamat Pentas maksimal 50 karakter',
            'deskripsi_advis.required' => 'Deskripsi Pentas wajib di isi',
            'deskripsi_advis.max' => 'Deskripsi Pentas maksimal 50 karakter',
            'tgl_advis.required' => 'Tanggal Pentas wajib di isi',
            'tgl_advis.date' => 'Format Tanggal Pentas tidak valid',
            'tgl_advis.after_or_equal' => 'Tanggal Pentas harus setelah atau sama dengan 7 hari dari tanggal sekarang',
            'tempat_advis.required' => 'Tempat Pentas wajib di isi',
            'tempat_advis.max' => 'Tempat pentas maksimal 30 karakter',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //get user
        $idUser = User::select("id_user")->whereRaw("BINARY email = ?",[$request->input('email')])->limit(1)->get()[0]['id_user'];
        //check seniman
        $seniman = Seniman::select('seniman.id_user', 'nomor_induk', 'status')->where('users.email', $request->input('email'))->where('seniman.id_seniman', $request->input('id_seniman'))->join('users', 'seniman.id_user', '=', 'users.id_user')->first();
        if(!$seniman){
            return response()->json(['status' => 'error', 'message' => 'Data seniman tidak ditemukan'], 404);
        }
        //check status seniman
        if($seniman->status == 'diajukan' || $seniman->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data Seniman sedang diproses'], 400);
        }else if($seniman->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data Seniman ditolak silahkan mengajukan ulang'], 400);
        }
        //add pentas
        $tambahPentas = SuratAdvis::create([
            'nomor_induk'=>$seniman->nomor_induk,
            'nama_advis' => $request->input('nama_advis'),
            'alamat_advis' => $request->input('alamat_advis'),
            'deskripsi_advis' => $request->input('deskripsi_advis'),
            'tgl_advis' => Carbon::createFromFormat('d-m-Y', $request->input('tgl_advis'))->format('Y-m-d'),
            'tempat_advis' => $request->input('tempat_advis'),
            'kode_verifikasi'=>'',
            'status' => 'diajukan',
            'catatan' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'id_seniman'=> $request->input('id_seniman'),
            'id_user' => $idUser,
        ]);
        if (!$tambahPentas) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan data Surat Advis'], 500);
        }
        return response()->json(['status'=>'success','message'=>'Data Surat Advis berhasil ditambahkan']);
    }
    public function editPentas(Request $request){
        $validator = Validator::make($request->only('id_pentas', 'deskripsi_advis', 'tgl_advis', 'tempat_advis'), [
            'id_pentas' => 'required',
            'deskripsi_advis' => 'required|max:100',
            'tgl_advis' => ['required', 'date', 'after_or_equal:' . now()->addDays(7)->toDateString()],
            'tempat_advis' => 'required|max:30',
        ], [
            'id_pentas.required' => 'ID Pentas wajib di isi',
            'deskripsi_advis.required' => 'Deskripsi Pentas wajib di isi',
            'deskripsi_advis.max' => 'Deskripsi Pentas maksimal 50 karakter',
            'tgl_advis.required' => 'Tanggal Pentas wajib di isi',
            'tgl_advis.date' => 'Format tanggal Pentas tidak valid',
            'tgl_advis.after_or_equal' => 'Tanggal Pentas harus setelah atau sama dengan 7 hari dari tanggal sekarang',
            'tempat_advis.required' => 'Tempat Pentas wajib di isi',
            'tempat_advis.max' => 'Tempat pentas maksimal 30 karakter',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check data surat advis
        $pentas = SuratAdvis::select('surat_advis.id_user', 'status')->where('users.email', $request->input('email'))->where('surat_advis.id_advis', $request->input('id_pentas'))->join('users', 'surat_advis.id_user', '=', 'users.id_user')->first();
        if (!$pentas) {
            return response()->json(['status' => 'error', 'message' => 'Data Pentas tidak ditemukan'], 404);
        }
        //check status
        if($pentas->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data Pentas sedang diproses'], 400);
        }else if($pentas->status == 'diterima' || $pentas->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data Pentas sudah diverifikasi'], 400);
        }

        //update pentas
        $updatedPentas = SuratAdvis::where('id_advis',$request->input('id_pentas'))->update([
            'deskripsi_advis' => $request->input('deskripsi_advis'),
            'tgl_advis' => Carbon::createFromFormat('d-m-Y', $request->input('tgl_advis'))->format('Y-m-d'),
            'tempat_advis' => $request->input('tempat_advis'),
            'status' => (is_null($pentas->status) || empty($pentas->status)) ? 'diajukan' : $pentas->status,
            'updated_at' => Carbon::now(),
        ]);
        if (!$updatedPentas) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui data Pentas'], 500);
        }
        return response()->json(['status' =>'success','message'=>'Data Pentas berhasil di perbarui']);
    }
    public function hapusPentas(Request $request){
        $validator = Validator::make($request->only('id_pentas'), [
            'id_pentas' => 'required',
        ], [
            'id_pentas.required' => 'ID Pentas wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check data pentas
        $pentas = SuratAdvis::select('surat_advis.id_user', 'status')->where('users.email', $request->input('email'))->where('surat_advis.id_advis', $request->input('id_pentas'))->join('users', 'surat_advis.id_user', '=', 'users.id_user')->first();
        if (!$pentas) {
            return response()->json(['status' => 'error', 'message' => 'Data Pentas tidak ditemukan'], 404);
        }
        //check status
        if($pentas->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data Pentas sedang diproses'], 400);
        }else if($pentas->status == 'diterima' || $pentas->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data Pentas sudah diverifikasi'], 400);
        }

        if (!SuratAdvis::where('id_advis',$request->input('id_pentas'))->delete()) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data SuratAdvis'], 500);
        }
        return response()->json(['status' => 'success', 'message' => 'Data Pentas berhasil dihapus']);
    }
}