<?php
namespace App\Http\Controllers\Page;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Events;
use App\Models\Seniman;
use App\Models\SewaTempat;
use App\Models\SuratAdvis;
use App\Models\Perpanjangan;
use DateTime;
class AdminController extends Controller
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
            foreach (['tanggal_lahir', 'tanggal_awal', 'tanggal_akhir'] as $dateField) {
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
    public function showPengguna(Request $request){
        $dataUser = User::select(
            'id_user',
            'nama_lengkap',
            'no_telpon',
            'jenis_kelamin',
            DB::raw('DATE(tanggal_lahir) AS tanggal_lahir'),
            'email',
        )->where('role', 'masyarakat')->get();
        unset($request->input('user_auth')['foto']);
        $dataShow = [
            'userAuth' => $request->input('user_auth'),
            'dataUser' => $dataUser,
        ];
        return view('page.pengguna.pengguna',$dataShow);
    }
    public function showDashboard(Request $request){
        $totalAdmin = User::whereNotIn('role', ['super admin', 'masyarakat'])->count();
        $totalPengguna = User::where('role','masyarakat')->count();
        $dataKalenderQuery = SewaTempat::select(
            'id_sewa',
            'nama_peminjam',
            'nama_tempat',
            'nama_kegiatan_sewa',
            DB::raw('DATE(tgl_awal_peminjaman) AS start_date'),
            DB::raw('DATE(tgl_akhir_peminjaman) AS end_date')
        )->where('status', 'diajukan')->get();
        foreach($dataKalenderQuery as $data){
            $sewa = array(
            'id' => $data['id_sewa'],
            'title' => $data['nama_kegiatan_sewa'],
            'peminjam' => $data['nama_peminjam'],
            'nama_tempat' => $data['nama_tempat'],
            'start' => $data['start_date'],
            'end' => $data['end_date'],
            );
            $dataKalender[] = $sewa;
        }
        unset($dataKalenderQuery);
        $totalEvent = Events::where('status','diajukan')->count();
        $totalSeniman = Seniman::where('status','diajukan')->count();
        $totalSewa = SewaTempat::where('status','diajukan')->count();
        $totalPentas = SuratAdvis::where('status','diajukan')->count();
        $totalPerpanjangan = Perpanjangan::where('status','diajukan')->count();
        unset($request->input('user_auth')['foto']);
        $dataShow = [
            'userAuth' => $request->input('user_auth'),
            'totalAdmin' => $totalAdmin,
            'totalPengguna' => $totalPengguna,
            'dataKalender' => $dataKalender,
            'totalEvent' => $totalEvent,
            'totalSeniman' => $totalSeniman,
            'totalSewa' => $totalSewa,
            'totalPentas' => $totalPentas,
            'totalPerpanjangan' => $totalPerpanjangan,
        ];
        return view('page.dashboard',$dataShow);
    }
    public function showProfile(Request $request){
        $userAuth = $request->input('user_auth');
        $dataShow = [
            'userAuth' => $userAuth,
            'tanggal_lahir' => $this->changeMonth(json_encode(['tanggal_lahir'=>$userAuth['tanggal_lahir']]))['tanggal_lahir']
        ];
        return view('page.profile',$dataShow);
    }
    //only admin
    public function showAdmin(Request $request){
        $userAuth = $request->input('user_auth');
        $adminData = User::select('id_user','nama_lengkap', 'no_telpon', 'jenis_kelamin', DB::raw('DATE(tanggal_lahir) AS tanggal_lahir'), 'tempat_lahir' ,'role', 'email')->whereNotIn('role', ['masyarakat', 'super admin'])->get();
        $dataShow = [
            'userAuth' => $userAuth,
            'adminData' => $adminData ?? '',
        ];
        return view('page.admin.admin',$dataShow);
    }
    public function showAdminTambah(Request $request){
        $userAuth = $request->input('user_auth');
        $dataShow = [
            'userAuth' => $userAuth,
        ];
        return view('page.admin.tambah',$dataShow);
    }
    public function showAdminEdit(Request $request, $idUser){
        $userAuth = $request->input('user_auth');
        $adminData = User::select('id_user','nama_lengkap', 'no_telpon', 'jenis_kelamin', DB::raw('DATE(tanggal_lahir) AS tanggal_lahir'), 'tempat_lahir' ,'role', 'email')->whereNotIn('role', ['masyarakat', 'super admin'])->whereRaw("BINARY id_user = ?",[$idUser])->limit(1)->get()[0];
        $dataShow = [
            'userAuth' => $userAuth,
            'adminData' => $adminData ?? '',
        ];
        return view('page.admin.edit',$dataShow);
    }
}