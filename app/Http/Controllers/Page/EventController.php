<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\DetailEvents;
use DateTime;
class EventController extends Controller
{
    private function changeMonth($inpDate){
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
        if (!is_array($inpDate)) {
            $inpDate = [$inpDate];
        }
        foreach ($inpDate as &$row) {
            foreach (['tanggal', 'tanggal_awal', 'tanggal_akhir'] as $dateField) {
                if (isset($row[$dateField]) && $row[$dateField] !== null) {
                    $date = new DateTime($row[$dateField]);
                    $monthNumber = $date->format('m');
                    $indonesianMonth = $monthTranslations[$monthNumber];
                    $formattedDate = $date->format('d') . ' ' . $indonesianMonth . ' ' . $date->format('Y');
                    $row[$dateField] = $formattedDate;
                }
            }
        }
        return (count($inpDate) === 1) ? $inpDate[0] : $inpDate;
    }
    public function showEvent(Request $request){
        $totalPengajuan = Events::where('status', 'diajukan')->orWhere('status', 'proses')->count();
        $totalRiwayat = Events::where('status', 'diterima')->orWhere('status', 'ditolak')->count();
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
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'eventsData'=>$eventsData,
        ];
        // echo json_encode($dataShow);
        // exit();
        return view('page.event.pengajuan',$dataShow);
    }
    public function showRiwayat(Request $request){
        $eventsData = $this->changeMonth(Events::select('events.id_event', 'nama_pengirim', 'nama_event', DB::raw('DATE(events.created_at) AS tanggal'), 'status')->join('detail_events', 'events.id_detail', '=', 'detail_events.id_detail')
        ->where(function ($query) {
            $query->where('status', 'diterima')->orWhere('status', 'ditolak');
        })->orderBy('id_event', 'DESC')->get());
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
        $dataShow = [
            'userAuth'=>$request->input('user_auth'),
            'eventsData'=>$eventsData,
        ];
        return view('page.event.detail_event',$dataShow);
    }
}