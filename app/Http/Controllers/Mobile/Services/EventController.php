<?php
namespace App\Http\Controllers\Mobile\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Events;
use App\Models\DetailEvents;
use Carbon\Carbon;
class EventController extends Controller
{
    public function tambahEvent(Request $request){
        $validator = Validator::make($request->only('nama_pengirim', 'nama_event', 'deskripsi', 'kategori_event', 'tanggal_awal', 'tanggal_akhir', 'tempat_event', 'link_pendaftaran', 'poster_event'), [
            'nama_pengirim' => 'required|min:6|max:50',
            'nama_event' => 'required|min:6|max:50',
            'deskripsi' => 'required|max:500',
            'kategori_event' => 'required|in:olahraga,seni,budaya',
            'tanggal_awal' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'tanggal_akhir' => ['required', 'date', 'after_or_equal:tanggal_awal', 'after_or_equal:' . now()->toDateString()],
            'tempat_event' => 'required|min:6|max:50',
            'link_pendaftaran' => 'nullable|max:2000',
            'poster_event' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'nama_pengirim.required' => 'Nama pengirim wajib di isi',
            'nama_pengirim.min' => 'Nama pengirim minimal 6 karakter',
            'nama_pengirim.max' => 'Nama pengirim maksimal 50 karakter',
            'nama_event.required' => 'Nama event wajib di isi',
            'nama_event.min' => 'Nama event minimal 6 karakter',
            'nama_event.max' => 'Nama event maksimal 50 karakter',
            'deskripsi.required' => 'Deskripsi wajib di isi',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter',
            'kategori_event.required' => 'Kategori event wajib di isi',
            'tanggal_awal.required' => 'Tanggal awal wajib di isi',
            'tanggal_awal.date' => 'Format tanggal awal tidak valid',
            'tanggal_awal.after_or_equal' => 'Tanggal awal harus setelah atau sama dengan tanggal sekarang',
            'tanggal_akhir.required' => 'Tanggal akhir wajib di isi',
            'tanggal_akhir.date' => 'Format tanggal akhir tidak valid',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal',
            'tempat_event.required' => 'Tempat event wajib di isi',
            'link_pendaftaran.max' => 'Link Pendaftaran maksimal 2000 karakter',
            'poster_event.required' => 'Poster event wajib di isi',
            'poster_event.image' => 'Poster event harus berupa gambar',
            'poster_event.mimes' => 'Format poster tidak valid. Gunakan format jpeg, png, jpg',
            'poster_event.max' => 'Ukuran poster tidak boleh lebih dari 5MB',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $idUser = User::select("id_user")->whereRaw("BINARY email = ?",[$request->input('email')])->limit(1)->get()[0]['id_user'];
        //process file
        if (!$request->hasFile('poster_event')) {
            return response()->json(['status'=>'error','message'=>'Poster event wajib di isi'], 400);
        }
        $file = $request->file('poster_event');
        if(!($file->isValid() && in_array($file->extension(), ['jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format poster tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $fileName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('event')->put('/' . $fileName, $fileData);
        //add event
        $detailEvent = DetailEvents::create([
            'nama_event' => $request->input('nama_event'),
            'deskripsi' => $request->input('deskripsi'),
            'tempat_event' => $request->input('tempat_event'),
            'tanggal_awal' => Carbon::createFromFormat('d-m-Y', $request->input('tanggal_awal'))->format('Y-m-d'),
            'tanggal_akhir' => Carbon::createFromFormat('d-m-Y', $request->input('tanggal_akhir'))->format('Y-m-d'),
            'link_pendaftaran' => $request->input('link_pendaftaran'),
            'poster_event' => $fileName,
        ]);
        if (!$detailEvent) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan data Detail Event'], 500);
        }
        $event = Events::create([
            'nama_pengirim' => $request->input('nama_pengirim'),
            'status' => 'diajukan',
            'catatan' => '',
            'id_detail' => $detailEvent->id_detail,
            'id_user' => $idUser,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);
        if (!$event) {
            $detailEvent->delete();
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan data Event'], 500);
        }
        return response()->json(['status'=>'success','message'=>'Data event berhasil ditambahkan']);
    }
    public function editEvent(Request $request){
        $validator = Validator::make($request->only('id_event','nama_pengirim', 'nama_event', 'deskripsi', 'kategori_event', 'tanggal_awal', 'tanggal_akhir', 'tempat_event', 'link_pendaftaran', 'poster_event'), [
            'id_event' => 'required',
            'nama_pengirim' => 'required|min:6|max:50',
            'nama_event' => 'required|min:6|max:50',
            'deskripsi' => 'required|max:500',
            'kategori_event' => 'required|in:olahraga,seni,budaya',
            'tanggal_awal' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'tanggal_akhir' => ['required', 'date', 'after_or_equal:tanggal_awal', 'after_or_equal:' . now()->toDateString()],
            'tempat_event' => 'required|min:6|max:50',
            'link_pendaftaran' => 'nullable|max:2000',
            'poster_event' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'id_event.required' => 'ID Event wajib di isi',
            'nama_pengirim.required' => 'Nama pengirim wajib di isi',
            'nama_pengirim.min' => 'Nama pengirim minimal 6 karakter',
            'nama_pengirim.max' => 'Nama pengirim maksimal 50 karakter',
            'nama_event.required' => 'Nama event wajib di isi',
            'nama_event.min' => 'Nama event minimal 6 karakter',
            'nama_event.max' => 'Nama event maksimal 50 karakter',
            'deskripsi.required' => 'Deskripsi wajib di isi',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter',
            'kategori_event.required' => 'Kategori event wajib di isi',
            'tanggal_awal.required' => 'Tanggal awal wajib di isi',
            'tanggal_awal.date' => 'Format tanggal awal tidak valid',
            'tanggal_awal.after_or_equal' => 'Tanggal awal harus setelah atau sama dengan tanggal sekarang',
            'tanggal_akhir.required' => 'Tanggal akhir wajib di isi',
            'tanggal_akhir.date' => 'Format tanggal akhir tidak valid',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal',
            'tempat_event.required' => 'Tempat event wajib di isi',
            'link_pendaftaran.max' => 'Link Pendaftaran maksimal 2000 karakter',
            'poster_event.required' => 'Poster event wajib di isi',
            'poster_event.image' => 'Poster event harus berupa gambar',
            'poster_event.mimes' => 'Format poster tidak valid. Gunakan format jpeg, png, jpg',
            'poster_event.max' => 'Ukuran poster tidak boleh lebih dari 5MB',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check data event
        $event = Events::select('events.id_user', 'status')->where('users.email', $request->input('email'))->where('events.id_event', $request->input('id_event'))->join('users', 'events.id_user', '=', 'users.id_user')->first();
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Data Event tidak ditemukan'], 404);
        }
        $detailEvent = DetailEvents::select('id_detail','poster_event')->find($request->input('id_event'));
        if (!$detailEvent) {
            return response()->json(['status' => 'error', 'message' => 'Data Detail Event tidak ditemukan'], 404);
        }
        //check status
        if($event->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data event sedang diproses'], 400);
        }else if($event->status == 'diterima' || $event->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data event sudah diverifikasi'], 400);
        }
        //process file
        if (!$request->hasFile('poster_event')) {
            return response()->json(['status'=>'error','message'=>'Poster event wajib di isi'], 400);
        }
        $file = $request->file('poster_event');
        if(!($file->isValid() && in_array($file->extension(), ['jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format poster tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $destinationPath = storage_path('app/event/');
        $fileToDelete = $destinationPath . $detailEvent->poster_event;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('event')->delete('/'.$detailEvent->poster_event);
        $fileName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('event')->put('/' . $fileName, $fileData);
        //update event
        $updatedDetailEvent = $detailEvent->update([
            'nama_event' => $request->input('nama_event'),
            'deskripsi' => $request->input('deskripsi'),
            'tempat_event' => $request->input('tempat_event'),
            'tanggal_awal' => Carbon::createFromFormat('d-m-Y', $request->input('tanggal_awal'))->format('Y-m-d'),
            'tanggal_akhir' => Carbon::createFromFormat('d-m-Y', $request->input('tanggal_akhir'))->format('Y-m-d'),
            'link_pendaftaran' => $request->input('link_pendaftaran'),
            'poster_event' => $fileName,
        ]);
        if (!$updatedDetailEvent) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui data Event'], 500);
        }
        $updatedEvent = $event->update([
            'nama_pengirim' => $request->input('nama_pengirim'),
            'status' => (is_null($event->status) || empty($event->status)) ? 'diajukan' : $event->status,
            'catatan' => $request->input('catatan'),
            'updated_at'=> Carbon::now()
        ]);
        if (!$updatedEvent) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui data Event'], 500);
        }
        return response()->json(['status' =>'success','message'=>'Data event berhasil di perbarui']);
    }
    public function hapusEvent(Request $request){
        $validator = Validator::make($request->only('id_event'), [
            'id_event' => 'required',
        ], [
            'id_event.required' => 'ID Event wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check data event
        $event = Events::select('events.id_user', 'status')->where('users.email', $request->input('email'))->where('events.id_event', $request->input('id_event'))->join('users', 'events.id_user', '=', 'users.id_user')->first();
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Data Event tidak ditemukan'], 404);
        }
        $detailEvent = DetailEvents::select('id_detail','poster_event')->find($request->input('id_event'));
        if (!$detailEvent) {
            return response()->json(['status' => 'error', 'message' => 'Data Detail Event tidak ditemukan'], 404);
        }
        //check status
        if($event->status == 'proses'){
            return response()->json(['status' => 'error', 'message' => 'Data event sedang diproses'], 400);
        }else if($event->status == 'diterima' || $event->status == 'ditolak'){
            return response()->json(['status' => 'error', 'message' => 'Data event sudah diverifikasi'], 400);
        }
        $destinationPath = storage_path('app/event/');
        $fileToDelete = $destinationPath . $detailEvent->poster_event;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('event')->delete('/'.$detailEvent->poster_event);
        if (!$detailEvent->delete()) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data Detail Event'], 500);
        }
        if (!Events::where('id_event',$request->input('id_event'))->delete()) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data Event'], 500);
        }
        return response()->json(['status' => 'success', 'message' => 'Data Event berhasil dihapus']);
    }
}