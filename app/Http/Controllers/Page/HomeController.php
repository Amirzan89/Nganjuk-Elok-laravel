<?php
namespace App\Http\Controllers\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ListTempat;
use App\Models\Events;
class HomeController extends Controller
{
    public function showHome(Request $request){
        $eventData = Events::select('nama_event', 'tempat_event', 'poster_event')->join('detail_events','events.id_detail', '=', 'detail_events.id_detail')->where('status', 'diterima')->orderBy('id_event','DESC')->limit(3)->get();
        $tempatData = ListTempat::select('id_tempat','nama_tempat', 'foto_tempat')->get();
        $dataShow = [
            'eventData' => $eventData ?? '',
            'tempatData' => $tempatData ?? '',
        ];
        return view('page.home',$dataShow);
    }
    public function showHome1(Request $request){
        $eventData = Events::select('nama_event', 'tempat_event', 'poster_event')->join('detail_events','events.id_detail', '=', 'detail_events.id_detail')->where('status', 'diterima')->orderBy('id_event','DESC')->get();
        $dataShow = [
            'eventData' => $eventData ?? '',
        ];
        return view('page.home1',$dataShow);
    }
}