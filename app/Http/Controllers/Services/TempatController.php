<?php
namespace App\Http\Controllers\Services;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\ListTempat;
class TempatController extends Controller
{
    public function tambahTempat(Request $request){
        $validator = Validator::make($request->only('nama_tempat', 'alamat', 'deskripsi', 'nama_pengelola', 'phone', 'foto'), [
            'nama_tempat' => 'required|min:6|max:50',
            'alamat' => 'required|min:6|max:50',
            'deskripsi' => 'required|max:500',
            'nama_pengelola' => 'required|min:6|max:50',
            'phone' => 'required|digits_between:10,13',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'nama_tempat.required' => 'Nama tempat wajib di isi',
            'alamat.required' => 'Alamat tempat wajib di isi',
            'deskripsi.required' => 'Deskripsi tempat wajib di isi',
            'nama_pengelola.required' => 'Pengelola wajib di isi',
            'phone.required' => 'Contact person wajib di isi',
            'phone.digits_between' => 'Contact person tidak boleh lebih dari 13 karakter',
            'foto.required' => 'Foto tempat wajib di isi',
            'foto.image' => 'Foto tempat harus berupa gambar',
            'foto.mimes' => 'Format foto tidak valid. Gunakan format jpeg, png, jpg',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 5MB',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //get last id
        $id = ListTempat::select('id_tempat')->orderBy('id_tempat','DESC')->limit(1)->get()[0]['id_tempat'];
        if (!$request->hasFile('foto')) {
            return response()->json(['status'=>'error','message'=>'Foto tempat wajib di isi'], 400);
        }
        if(app()->environment('local')){
            $destinationPath = public_path('img/tempat');
        }else{
            $destinationPath = base_path('../public_html/public/img/tempat/');
        }
        $foto = $request->file('foto');
        $filename = $id . '.' . $foto->getClientOriginalExtension();
        $foto->move($destinationPath, $filename);
        $ins = ListTempat::insert([
            'nama_tempat'=>$request->input('nama_tempat'),
            'alamat_tempat'=>$request->input('alamat'),
            'deskripsi_tempat'=>$request->input('deskripsi'),
            'pengelola'=>$request->input('nama_pengelola'),
            'contact_person'=>$request->input('phone'),
            'foto_tempat'=>'/'.$filename,
        ]);
        if(!$ins){
            return response()->json(['status'=>'error','message'=>'Gagal menambahkan data Tempat'], 500);
        }
        return response()->json(['status'=>'success','message'=>'Data Tempat berhasil ditambahkan']);
    }
    public function editTempat(Request $request){
        $validator = Validator::make($request->only('id_tempat','nama_tempat', 'alamat', 'deskripsi', 'nama_pengelola', 'phone', 'foto'), [
            'id_tempat' => 'required',
            'nama_tempat' => 'required|min:6|max:50',
            'alamat' => 'required|min:6|max:50',
            'deskripsi' => 'required|min:50|max:500',
            'nama_pengelola' => 'required|min:6|max:50',
            'phone' => 'required|digits_between:13|integer',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'id_tempat.required' => 'ID tempat wajib di isi',
            'nama_tempat.required' => 'Nama tempat wajib di isi',
            'alamat.required' => 'Alamat tempat wajib di isi',
            'deskripsi.required' => 'Deskripsi tempat wajib di isi',
            'nama_pengelola.required' => 'Pengelola wajib di isi',
            'phone.required' => 'Contact person wajib di isi',
            'phone.digits_between' => 'Contact person tidak boleh lebih dari 13 karakter',
            'phone.integer' => 'Contact person harus berupa angka',
            'foto.required' => 'Foto tempat wajib di isi',
            'foto.image' => 'Foto tempat harus berupa gambar',
            'foto.mimes' => 'Format foto tidak valid. Gunakan format jpeg, png, jpg, atau gif',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 5MB',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $tempat = ListTempat::find($request->input('id_tempat'));
        if ($tempat) {
            return response()->json(['status' =>'error','message'=>'Data Tempat tidak ditemukan'], 400);
        }
        if (!$request->hasFile('foto')) {
            return response()->json(['status'=>'error','message'=>'Foto tempat wajib di isi'], 400);
        }
        if(app()->environment('local')){
            $destinationPath = public_path('img/tempat');
        }else{
            $destinationPath = base_path('../public_html/public/img/tempat/');
        }
        if (file_exists($destinationPath.$tempat->foto_tempat)){
            unlink($destinationPath. $tempat->foto_tempat);
        }
        $foto = $request->file('foto');
        $filename = $request->input('id_tempat') . '.' . $foto->getClientOriginalExtension();
        $foto->move($destinationPath, $filename);
        $edit = $tempat->update([
            'nama_tempat'=>$request->input('nama_tempat'),
            'alamat_tempat'=>$request->input('alamat_tempat'),
            'deskripsi_tempat'=>$request->input('deskripsi_tempat'),
            'pengelola'=>$request->input('nama_pengelola'),
            'contact_person'=>$request->input('phone'),
            'foto_tempat'=>'/'.$filename,
        ]);
        if(!$edit){
            return response()->json(['status' =>'error','message'=>'Gagal memperbarui data Tempat'], 500);
        }
        return response()->json(['status' =>'success','message'=>'Data Tempat berhasil di perbarui']);
    }
    public function deleteTempat(Request $request){
        $validator = Validator::make($request->only('id_tempat'), [
            'id_tempat' => 'required',
        ], [
            'id_tempat.required' => 'ID tempat wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $tempat = ListTempat::find($request->input('id_tempat'));
        if (!$tempat) {
            return response()->json(['status' => 'error', 'message' => 'Data Tempat tidak ditemukan'], 400);
        }
        if (app()->environment('local')) {
            $destinationPath = public_path('img/tempat');
        } else {
            $destinationPath = base_path('../public_html/public/img/tempat/');
        }
        if (file_exists($destinationPath . $tempat->foto_tempat)) {
            unlink($destinationPath . $tempat->foto_tempat);
        }
        $tempat->delete();
        return response()->json(['status' => 'success', 'message' => 'Data Tempat berhasil dihapus']);
    }
}