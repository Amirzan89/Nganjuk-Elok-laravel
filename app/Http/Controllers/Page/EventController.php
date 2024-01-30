<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Events;
use DateTime;
class EventController extends Controller
{
    private function changeMonth($inpDate){
        $inpDate = json_decode($inpDate, true);
        $monthTranslations = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        // Check if it's an associative array (single data)
        if (array_keys($inpDate) !== range(0, count($inpDate) - 1)) {
            foreach (['tanggal_awal', 'tanggal_akhir'] as $dateField) {
                if (isset($inpDate[$dateField]) && $inpDate[$dateField] !== null) {
                    $date = new DateTime($inpDate[$dateField]);
                    $monthNumber = $date->format('m');
                    $indonesianMonth = $monthTranslations[$monthNumber];
                    $formattedDate = $date->format('d') . ' ' . $indonesianMonth . ' ' . $date->format('Y');
                    $inpDate[$dateField] = $formattedDate;
                }
            }
        } else {
            $processedData = [];
            foreach ($inpDate as $inpDateRow) {
                $processedRow = $inpDateRow;
                foreach (['tanggal', 'tanggal_awal', 'tanggal_akhir'] as $dateField) {
                    if (isset($processedRow[$dateField]) && $processedRow[$dateField] !== null) {
                        $date = new DateTime($processedRow[$dateField]);
                        $monthNumber = $date->format('m');
                        $indonesianMonth = $monthTranslations[$monthNumber];
                        $formattedDate = $date->format('d') . ' ' . $indonesianMonth . ' ' . $date->format('Y');
                        $processedRow[$dateField] = $formattedDate;
                    }
                }
                $processedData[] = $processedRow;
            }
            $inpDate = $processedData;
        }
        return $inpDate;
    }
    public function getEventPengajuan(Request $request){
        $validator = Validator::make($request->only('tanggal'), [
            'tanggal'=>'required',
        ], [
            'tanggal.required' => 'Tanggal wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        if($request->input('tanggal') === 'semua'){
            $event = Events::select('events.id_event', 'nama_pengirim', 'nama_event', DB::raw('DATE(events.created_at) AS tanggal'),'status')->where(function ($query) {
                $query->where('status', 'diajukan')->orWhere('status', 'proses');
            })->join('detail_events', 'events.id_detail', '=', 'detail_events.id_detail')->orderBy('events.id_event','DESC')->get();
        }else{
            $tanggal = explode('-', $request->input('tanggal')); 
            $event = Events::select('events.id_event', 'nama_pengirim', 'nama_event', DB::raw('DATE(events.created_at) AS tanggal'),'status')->where(function ($query) {
                $query->where('status', 'diajukan')->orWhere('status', 'proses');
            })->whereRaw('MONTH(updated_at) = ?',[$tanggal[0]])->whereRaw('YEAR(updated_at) = ?',[$tanggal[1]])->join('detail_events', 'events.id_detail', '=', 'detail_events.id_detail')->orderBy('events.id_event','DESC')->get();
        }
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Data event tidak ditemukan'], 400);
        }
        return response()->json(['status'=>'success','data'=>$this->changeMonth($event)]);
    }
    public function getEventRiwayat(Request $request){
        $validator = Validator::make($request->only('tanggal'), [
            'tanggal'=>'required',
        ], [
            'tanggal.required' => 'Tanggal wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        if($request->input('tanggal') === 'semua'){
            $event = Events::select('events.id_event', 'nama_pengirim', 'nama_event', DB::raw('DATE(events.created_at) AS tanggal'),'status')->where(function ($query) {
                $query->where('status', 'ditolak')->orWhere('status', 'diterima');
            })->join('detail_events', 'events.id_detail', '=', 'detail_events.id_detail')->orderBy('events.id_event','DESC')->get();
        }else{
            $tanggal = explode('-', $request->input('tanggal'));
            $event = Events::select('events.id_event', 'nama_pengirim', 'nama_event', DB::raw('DATE(events.created_at) AS tanggal'),'status')->where(function ($query) {
                $query->where('status', 'ditolak')->orWhere('status', 'diterima');
            })->whereRaw('MONTH(updated_at) = ?',[$tanggal[0]])->whereRaw('YEAR(updated_at) = ?',[$tanggal[1]])->join('detail_events', 'events.id_detail', '=', 'detail_events.id_detail')->orderBy('events.id_event','DESC')->get();
        }
        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Data event tidak ditemukan'], 400);
        }
        return response()->json(['status'=>'success','data'=>$this->changeMonth($event)]);
    }
    public function showEvent(Request $request){
        $totalPengajuan = Events::where('status', 'diajukan')->orWhere('status', 'proses')->count();
        $totalRiwayat = Events::where('status', 'diterima')->orWhere('status', 'ditolak')->count();
        unset($request->input('user_auth')['foto']);
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'totalPengajuan'=>$totalPengajuan,
            'totalRiwayat'=>$totalRiwayat,
        ];
        return view('page.event.event',$dataShow);
    }
    public function showFormulir(Request $request){
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
        ];
        return view('page.event.formulir',$dataShow);
    }
    public function showPengajuan(Request $request){
        $eventsData = $this->changeMonth(Events::select('events.id_event', 'nama_pengirim', 'nama_event', DB::raw('DATE(events.created_at) AS tanggal'), 'status')->join('detail_events', 'events.id_detail', '=', 'detail_events.id_detail')
        ->where(function ($query) {
            $query->where('status', 'diajukan')->orWhere('status', 'proses');
        })->orderBy('id_event', 'DESC')->get());
        unset($request->input('user_auth')['foto']);
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'eventsData'=>$eventsData,
        ];
        return view('page.event.pengajuan',$dataShow);
    }
    public function showRiwayat(Request $request){
        $eventsData = $this->changeMonth(Events::select('events.id_event', 'nama_pengirim', 'nama_event', DB::raw('DATE(events.created_at) AS tanggal'), 'status')->join('detail_events', 'events.id_detail', '=', 'detail_events.id_detail')
        ->where(function ($query) {
            $query->where('status', 'diterima')->orWhere('status', 'ditolak');
        })->orderBy('id_event', 'DESC')->get());
        unset($request->input('user_auth')['foto']);
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'eventsData'=>$eventsData,
        ];
        return view('page.event.riwayat',$dataShow);
    }
    public function showDetail(Request $request, $eventId){
        $eventsData = $this->changeMonth(Events::select(
            'events.id_event',
            'nama_pengirim',
            'status',
            'catatan',
            'events.id_detail',
            'nama_event',
            'deskripsi',
            'tempat_event',
            DB::raw('DATE(tanggal_awal) AS tanggal_awal'),
            DB::raw('DATE(tanggal_akhir) AS tanggal_akhir'),
            'link_pendaftaran'
        )->join('detail_events', 'events.id_detail', '=', 'detail_events.id_detail')->where('events.id_event', '=', $eventId)->limit(1)->get()[0]);
        unset($request->input('user_auth')['foto']);
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'eventsData'=>$eventsData,
        ];
        return view('page.event.detail',$dataShow);
    }
}