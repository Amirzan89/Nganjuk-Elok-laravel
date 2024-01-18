<?php

namespace App\Http\Controllers\Mobile\Services;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\DetailEvents;
class EventController extends Controller
{
    public function tambahEvent(Request $request){
        $validator = Validator::make($request->only('nama_pengirim', 'nama_event', 'deskripsi', 'kategori_event', 'tanggal_awal', 'tanggal_akhir', 'tempat_event', 'poster_event'), [
            'nama_pengirim' => 'required|min:6|max:50',
            'nama_event' => 'required|min:6|max:50',
            'deskripsi' => 'required|max:500',
            'kategori_event' => 'required|in:olahraga,seni,budaya',
            'tanggal_awal' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'tanggal_akhir' => ['required', 'date', 'after_or_equal:tanggal_awal', 'after_or_equal:' . now()->toDateString()],
            'tempat_event' => 'required|min:6|max:50',
            'poster_event' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'nama_pengirim.required' => 'Nama pengirim wajib di isi',
            'nama_event.required' => 'Nama event wajib di isi',
            'deskripsi.required' => 'Deskripsi wajib di isi',
            'kategori_event.required' => 'Kategori event wajib di isi',
            'tanggal_awal.required' => 'Tanggal awal wajib di isi',
            'tanggal_awal.date' => 'Format tanggal awal tidak valid',
            'tanggal_awal.after_or_equal' => 'Tanggal awal harus setelah atau sama dengan tanggal sekarang',
            'tanggal_akhir.required' => 'Tanggal akhir wajib di isi',
            'tanggal_akhir.date' => 'Format tanggal akhir tidak valid',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal',
            'tempat_event.required' => 'Tempat event wajib di isi',
            'poster_event.required' => 'Poster event wajib di isi',
            'poster_event.image' => 'Poster event harus berupa gambar',
            'poster_event.mimes' => 'Format poster tidak valid. Gunakan format jpeg, png, jpg',
            'poster_event.max' => 'Ukuran poster tidak boleh lebih dari 5MB',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
            }
            return response()->json(['status'=>'error','message'=>$errors], 400);
        }
        //get last id
        $id = Events::select('id_event')->orderBy('id_event','DESC')->limit(1)->get()[0]['id_event'];
        if (!$request->hasFile('foto')) {
            return response()->json(['status'=>'error','message'=>'Foto tempat wajib di isi'], 400);
        }
        if(app()->environment('local')){
            $destinationPath = public_path('img/event');
        }else{
            $destinationPath = base_path('../public_html/public/img/event/');
        }
        $foto = $request->file('foto');
        $filename = $id . '.' . $foto->getClientOriginalExtension();
        $foto->move($destinationPath, $filename);
        $detailEvent = DetailEvents::create([
            'nama_event' => $request->input('nama_event'),
            'deskripsi' => $request->input('deskripsi'),
            'tempat_event' => $request->input('tempat_event'),
            'tanggal_awal' => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
            'link_pendaftaran' => $request->input('link_pendaftaran'),
            'poster_event' => $request->input('poster_event'),
            // Other fields...
        ]);
        if (!$detailEvent) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan data Detail Event'], 500);
        }
        $event = Events::create([
            'nama_pengirim' => $request->input('nama_pengirim'),
            'status' => $request->input('status'),
            'catatan' => $request->input('catatan'),
            'id_detail' => $detailEvent->id_detail,
            'id_user' => $request->input('id_user'),
        ]);
        if (!$event) {
            $detailEvent->delete();
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan data Event'], 500);
        }
        return response()->json(['status'=>'success','message'=>'Data Tempat berhasil diperbarui']);
    }
    public function editEvent(Request $request){
        ['id_event','nama_event','deskripsi','kategori_event','tanggal_awal','tanggal_akhir','poster_event'];
        $validator = Validator::make($request->only('nama_pengirim', 'nama_event', 'deskripsi', 'kategori_event', 'tanggal_awal', 'tanggal_akhir', 'tempat_event', 'poster_event'), [
            'id_event' => 'required',
            'nama_pengirim' => 'required|min:6|max:50',
            'nama_event' => 'required|min:6|max:50',
            'deskripsi' => 'required|max:500',
            'kategori_event' => 'required|in:olahraga,seni,budaya',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'tempat_event' => 'required|min:6|max:50',
            'poster_event' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'id_event.required' => 'ID Event wajib di isi',
            'nama_pengirim.required' => 'Nama pengirim wajib di isi',
            'nama_event.required' => 'Nama event wajib di isi',
            'deskripsi.required' => 'Deskripsi wajib di isi',
            'kategori_event.required' => 'Kategori event wajib di isi',
            'tanggal_awal.required' => 'Tanggal awal wajib di isi',
            'tanggal_awal.date' => 'Format tanggal awal tidak valid',
            'tanggal_akhir.required' => 'Tanggal akhir wajib di isi',
            'tanggal_akhir.date' => 'Format tanggal akhir tidak valid',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal awal',
            'tempat_event.required' => 'Tempat event wajib di isi',
            'poster_event.required' => 'Poster event wajib di isi',
            'poster_event.image' => 'Poster event harus berupa gambar',
            'poster_event.mimes' => 'Format poster tidak valid. Gunakan format jpeg, png, jpg',
            'poster_event.max' => 'Ukuran poster tidak boleh lebih dari 5MB',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
            }
            return response()->json(['status' =>'error','message'=>$errors], 400);
        }
        $tempat = Events::find($request->input('id_event'));
        if ($tempat) {
            return response()->json(['status' =>'error','message'=>'Data Tempat tidak ditemukan'], 400);
        }
        if (!$request->hasFile('foto')) {
            return response()->json(['status'=>'error','message'=>'Foto tempat wajib di isi'], 400);
        }
        if(app()->environment('local')){
            $destinationPath = public_path('img/event');
        }else{
            $destinationPath = base_path('../public_html/public/img/event/');
        }
        if (file_exists($destinationPath.$tempat->foto_tempat)){
            unlink($destinationPath. $tempat->foto_tempat);
        }
        $foto = $request->file('foto');
        $filename = $request->input('id_event') . '.' . $foto->getClientOriginalExtension();
        $foto->move($destinationPath, $filename);
        $detailEvent = DetailEvents::select('id_detail')->find($detailEventId);
        if (!$detailEvent) {
            return response()->json(['status' => 'error', 'message' => 'Detail Event not found'], 404);
        }
        $updatedDetailEvent = $detailEvent->update([
            'nama_event' => $request->input('nama_event'),
            'deskripsi' => $request->input('deskripsi'),
            'tempat_event' => $request->input('tempat_event'),
            'tanggal_awal' => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
            'link_pendaftaran' => $request->input('link_pendaftaran'),
            'poster_event' => $request->input('poster_event'),
        ]);
        if (!$updatedDetailEvent) {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Detail Event'], 500);
        }
        $event = Events::select('id_event')->find($eventId);
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Event not found'], 404);
        }
        $updatedEvent = $event->update([
            'nama_pengirim' => $request->input('nama_pengirim'),
            'status' => $request->input('status'),
            'catatan' => $request->input('catatan'),
            // Other fields...
        ]);
        return response()->json(['status' =>'success','message'=>'Data Tempat berhasil di perbarui']);
    }
    public function deleteEvent(Request $request){
        ['id_event'];
        $validator = Validator::make($request->only('id_event'), [
            'id_event' => 'required',
        ], [
            'id_event.required' => 'ID tempat wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
            }
            return response()->json(['status' => 'error', 'message' => $errors], 400);
        }
        $eventData = Events::select('foto_tempat')->find($request->input('id_event'));
        if (!$eventData) {
            return response()->json(['status' => 'error', 'message' => 'Data Tempat tidak ditemukan'], 400);
        }
        if (app()->environment('local')) {
            $destinationPath = public_path('img/tempat');
        } else {
            $destinationPath = base_path('../public_html/public/img/tempat/');
        }
        if (file_exists($destinationPath . $eventData->foto_tempat)) {
            unlink($destinationPath . $eventData->foto_tempat);
        }
        $eventData->delete();
        return response()->json(['status' => 'success', 'message' => 'Data Tempat berhasil dihapus']);
    }
}