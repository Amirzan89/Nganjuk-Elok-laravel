<?php
namespace App\Http\Controllers\Services;
use App\Http\Controllers\Controller;
use App\Models\HistoriNis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\KategoriSeniman;
use App\Models\Seniman;
use App\Models\Perpanjangan;
use Exception;
class SenimanController extends Controller
{
    private static $constID = '411.302';
    private static $jsonFile;
    public function __construct(){
        self::$jsonFile = storage_path('app/kategori_seniman/kategori_seniman.json');
    }
    // private function kategoriCache($data, $con){
    //     $fileExist = Redis::exists('kategori_seniman');
    //     if($con == 'get'){
    //         //get kategori seniman
    //         $jsonData = json_decode(file_get_contents(self::$jsonFile), true);
    //         $result = null;
    //         foreach($jsonData as $key => $item){
    //             if (isset($item['id_kategori_seniman']) && $item['id_kategori_seniman'] == $data['id_kategori']) {
    //                 $result = $jsonData[$key];
    //             }
    //         }
    //         if($result === null){
    //             throw new Exception('Data kategori tidak ditemukan');
    //         }
    //         return $result;
    //     }else if($con == 'tambah'){
    //         //check if cache exist
    //         if (!$fileExist) {
    //             //if cache is delete will make new json cache
    //             $kategoriData = json_decode(KategoriSeniman::get(),true);
    //             Redis::set('kategori_seniman', json_encode($kategoriData, JSON_PRETTY_PRINT));
    //             Redis::expire('kategori_seniman',604800);
    //         }else{
    //             //tambah kategori seniman
    //             $jsonData = json_decode(Redis::get('kategori_seniman'),true);
    //             $new[$data['id_kategori_seniman']] = $data;
    //             $jsonData = array_merge($jsonData, $new);
    //             Redis::set('kategori_seniman', json_encode($jsonData, JSON_PRETTY_PRINT));
    //             Redis::expire('kategori_seniman',604800);
    //         }
    //     }else if($con == 'update'){
    //         //update kategori seniman
    //         $jsonData = json_decode(Redis::get('kategori_seniman'),true);
    //         foreach($jsonData as $key => $item){
    //             if (isset($item['id_kategori_seniman']) && $item['id_kategori_seniman'] == $data['id_kategori']) {
    //                 $jsonData[$key] = $data;
    //             }
    //         }
    //         $jsonData = array_values($jsonData);
    //         Redis::set('kategori_seniman', json_encode($jsonData, JSON_PRETTY_PRINT));
    //         Redis::expire('kategori_seniman',604800);
    //     }else if($con == 'hapus'){
    //         //hapus kategori seniman
    //         $jsonData = json_decode(Redis::get('kategori_seniman'),true);
    //         foreach($jsonData as $key => $item){
    //             if (isset($item['id_kategori_seniman']) && $item['id_kategori_seniman'] == $data['id_kategori']) {
    //                 unset($jsonData[$key]);
    //             }
    //         }
    //         $jsonData = array_values($jsonData);
    //         Redis::set('kategori_seniman', json_encode($jsonData, JSON_PRETTY_PRINT));
    //         Redis::expire('kategori_seniman',604800);
    //     }
    // }
    private function kategoriFile($data, $con){
        $fileExist = file_exists(self::$jsonFile);
        //check if file exist
        if (!$fileExist) {
            //if file is delete will make new json file
            $kategoriData = json_decode(KategoriSeniman::get(),true);
            if (!file_put_contents(self::$jsonFile,json_encode($kategoriData, JSON_PRETTY_PRINT))) {
                throw new Exception('Gagal menyimpan file sistem');
            }
        }
        if($con == 'get'){
            //get kategori seniman
            $jsonData = json_decode(file_get_contents(self::$jsonFile), true);
            $result = null;
            foreach($jsonData as $key => $item){
                if (isset($item['id_kategori_seniman']) && $item['id_kategori_seniman'] == $data['id_kategori']) {
                    $result = $jsonData[$key];
                }
            }
            if($result === null){
                throw new Exception('Data kategori tidak ditemukan');
            }
            return $result;
        }else if($con == 'tambah'){
            //tambah kategori seniman
            $jsonData = json_decode(file_get_contents(self::$jsonFile),true);
            $new[$data['id_kategori_seniman']] = $data;
            $jsonData = array_merge($jsonData, $new);
            file_put_contents(self::$jsonFile,json_encode($jsonData, JSON_PRETTY_PRINT));
        }else if($con == 'update'){
            //update kategori seniman
            $jsonData = json_decode(file_get_contents(self::$jsonFile),true);
            foreach($jsonData as $key => $item){
                if (isset($item['id_kategori_seniman']) && $item['id_kategori_seniman'] == $data['id_kategori']) {
                    $newData = [
                        'id_kategori_seniman' => $data['id_kategori'],
                        'nama_kategori' => $data['nama_kategori_seniman'],
                        'singkatan_kategori' => $data['singkatan_kategori']
                    ];
                    $jsonData[$key] = $newData;
                    break;
                }
            }
            $jsonData = array_values($jsonData);
            file_put_contents(self::$jsonFile,json_encode($jsonData, JSON_PRETTY_PRINT));
        }else if($con == 'hapus'){
            //hapus kategori seniman
            $jsonData = json_decode(file_get_contents(self::$jsonFile),true);
            foreach($jsonData as $key => $item){
                if (isset($item['id_kategori_seniman']) && $item['id_kategori_seniman'] == $data['id_kategori']) {
                    unset($jsonData[$key]);
                }
            }
            $jsonData = array_values($jsonData);
            file_put_contents(self::$jsonFile,json_encode($jsonData, JSON_PRETTY_PRINT));
        }
    }
    public function tambahKategoriSeniman(Request $request){
        $validator = Validator::make($request->only('nama', 'singkatan'), [
            'nama' => 'required|max:45',
            'singkatan' => 'required|max:6',
        ], [
            'nama.required' => 'Nama kategori wajib di isi',
            'nama.max' => 'Nama kategori maksimal 45 karakter',
            'singkatan.required' => 'Singkatan kategori wajib di isi',
            'singkatan.max' => 'Singkatan kategori maksimal 6 karakter',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $ins = KategoriSeniman::insertGetId([
            'nama_kategori'=>$request->input('nama'),
            'singkatan_kategori'=>strtoupper($request->input('singkatan')),
        ]);
        if(!$ins){
            return response()->json(['status'=>'error','message'=>'Gagal menambahkan data Kategori Seniman'], 500);
        }
        $this->kategoriFile([
            'id_kategori' => $ins,
            'nama_kategori_seniman'=>$request->input('nama'),
            'singkatan_kategori'=>strtoupper($request->input('singkatan'))
        ],'tambah');
        return response()->json(['status'=>'success','message'=>'Data Kategori Seniman berhasil diperbarui']);
    }
    public function editKategoriSeniman(Request $request){
        $validator = Validator::make($request->only('id_kategori','nama', 'singkatan'), [
            'id_kategori' => 'required',
            'nama' => 'required|max:45',
            'singkatan' => 'required|max:6',
        ], [
            'nama.required' => 'Nama kategori wajib di isi',
            'nama.max' => 'Nama kategori maksimal 45 karakter',
            'singkatan.required' => 'Singkatan kategori wajib di isi',
            'singkatan.max' => 'Singkatan kategori maksimal 6 karakter',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $kategori = KategoriSeniman::find(['id_kategori_seniman' => $request->input('id_kategori')]);
        if (!$kategori) {
            return response()->json(['status' =>'error','message'=>'Data Kategori Seniman tidak ditemukan'], 400);
        }
        $edit = KategoriSeniman::where('id_kategori_seniman',$request->input('id_kategori'))->update([
            'nama_kategori'=>$request->input('nama'),
            'singkatan_kategori'=>strtoupper($request->input('singkatan')),
        ]);
        if(!$edit){
            return response()->json(['status' =>'error','message'=>'Gagal memperbarui data Kategori Seniman'], 500);
        }
        $this->kategoriFile([
            'id_kategori' => $request->input('id_kategori'),
            'nama_kategori_seniman' => $request->input('nama'),
            'singkatan_kategori' => strtoupper($request->input('singkatan'))
        ],'update');
        return response()->json(['status' =>'success','message'=>'Data Kategori Seniman berhasil di perbarui']);
    }
    public function deleteKategoriSeniman(Request $request){
        $validator = Validator::make($request->only('id_kategori'), [
            'id_kategori' => 'required',
        ], [
            'id_kategori.required' => 'ID kategori seniman wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $kategori = KategoriSeniman::find(['id_kategori_seniman' => $request->input('id_kategori')]);
        if (!$kategori) {
            return response()->json(['status' => 'error', 'message' => 'Data Kategori Seniman tidak ditemukan'], 400);
        }
        if (!KategoriSeniman::where('id_kategori_seniman',$request->input('id_kategori'))->delete()) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data kategori seniman'], 500);
        }
        $this->kategoriFile(['id_kategori' => $request->input('id_kategori')],'hapus');
        return response()->json(['status' => 'success', 'message' => 'Data Kategori Seniman berhasil dihapus']);
    }
    private function generateNIS($data,$desc){
        try{
            //check if cache have kategori
            // if(Redis::exists('kategori_seniman')){
            //     $kategoriData = Redis::get('kategori_seniman');
            // }else{
                //get data from database
                //     Redis::set('kategori_seniman', json_encode($kategoriData));
                //     Redis::expire('kategori_seniman',604800); // 1 week
                // }
            $kategoriData = $this->kategoriFile(['id_kategori'=>$data['id_kategori']],'get');
            //get last NIS
            date_default_timezone_set('Asia/Jakarta');
            if ($desc == 'diterima') {
                $total = Seniman::where('nomor_induk', 'like', '%/' . date('Y'))->where('id_kategori_seniman', $data['id_kategori'])->count();
            } elseif ($desc == 'perpanjangan') {
                $total = Seniman::where('nomor_induk', 'like', '%/' . (date('Y') + 1))->where('id_kategori_seniman', $data['id_kategori'])->count();
            } else {
                throw new Exception('Description invalid');
            }
            if(!$total){
                $total = 1;
            }else{
                $total++;
            }
            $total = str_pad($total, 3, '0', STR_PAD_LEFT);
            if($desc == 'diterima'){
                $nis = $kategoriData['singkatan_kategori'].'/'.$total.'/'.self::$constID.'/'.date('Y');
            }else if($desc == 'perpanjangan'){
                $nis = $kategoriData['singkatan_kategori'].'/'.$total.'/'.self::$constID.'/'.(date('Y')+1);
            }
            return $nis;
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson->message,
                );
            }
            return response()->json($responseData,isset($errorJson['code']) ? $errorJson['code'] : 400);
        }
    }
    public function prosesSeniman(Request $request){
        try{
            $validator = Validator::make($request->only('id_seniman','keterangan','catatan'), [
                'id_seniman' => 'required',
                'keterangan' => 'required|in:proses,diterima,ditolak',
                'catatan' => 'nullable',
            ], [
                'id_seniman.required' => 'ID Seniman wajib di isi',
                'keterangan.required' => 'Keterangan wajib di isi',
                'keterangan.in' => 'Keterangan invalid',
            ]);
            if ($validator->fails()) {
                $errors = [];
                foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                    $errors[$field] = $errorMessages[0];
                    break;
                }
                return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
            }
            $ketInput = $request->input('keterangan');
            $catatanInput = $request->input('catatan');
            $seniman = Seniman::select('status','id_kategori_seniman')->whereRaw("BINARY id_seniman = ?",[$request->input('id_seniman')])->first();
            if (!$seniman) {
                return response()->json(['status' => 'error', 'message' => 'Seniman tidak ditemukan'], 400);
            }
            $statusDB = $seniman->status;

            //check status
            if($ketInput ==  'proses' && ($statusDB == 'diterima' || $statusDB == 'ditolak')){
                return response()->json(['status' => 'error', 'message' => 'Data sudah diverifikasi'], 400);
            }
            if($statusDB ==  'diajukan' && ($ketInput == 'diterima' || $ketInput == 'ditolak')){
                return response()->json(['status' => 'error', 'message' => 'Data harus di proses'], 400);
            }
            if($ketInput ==  'ditolak' && $statusDB == 'diterima'){
                return response()->json(['status' => 'error', 'message' => 'Data sudah diverifikasi'], 400);
            }
            if($ketInput ==  'diterima' && $statusDB == 'ditolak'){
                return response()->json(['status' => 'error', 'message' => 'Data sudah diverifikasi'], 400);
            }
            if($ketInput == 'ditolak' && (empty($catatanInput) && is_null($catatanInput))){
                return response()->json(['status' => 'error', 'message' => 'Catatan harus di isi !'], 400);
            }

            // Update  seniman using a raw query
            $updateQuery = Seniman::whereRaw("BINARY id_seniman = ?", [$request->input('id_seniman')])
            ->update([
                'nomor_induk' => $ketInput == 'diterima' ? $this->generateNIS(['id_kategori' => $seniman->id_kategori_seniman],'diterima') : '',
                'status' => $ketInput == 'proses' ? 'proses' : ($ketInput == 'diterima' ? 'diterima' : 'ditolak'),
                'catatan' => ($ketInput == 'proses' || $ketInput == 'diterima') ? '' : $catatanInput,
            ]);
            if ($updateQuery <= 0) {
                return response()->json(['status' => 'error', 'message' => 'Status gagal diubah'], 500);
            }
            return response()->json(['status' => 'success', 'message' => 'Status berhasil diubah']);
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson->message,
                );
            }
            return response()->json($responseData,isset($errorJson['code']) ? $errorJson['code'] : 400);
        }
    }
    public function prosesPerpanjangan(Request $request){
        try{
            $validator = Validator::make($request->only('id_perpanjangan','keterangan','catatan'), [
                'id_perpanjangan' => 'required',
                'keterangan' => 'required|in:proses,diterima,ditolak',
                'catatan' => 'nullable',
            ], [
                'id_perpanjangan.required' => 'ID Perpanjangan wajib di isi',
                'keterangan.required' => 'Keterangan wajib di isi',
                'keterangan.in' => 'Keterangan invalid',
            ]);
            if ($validator->fails()) {
                $errors = [];
                foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                    $errors[$field] = $errorMessages[0];
                    break;
                }
                return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
            }
            $ketInput = $request->input('keterangan');
            $catatanInput = $request->input('catatan');

            //check perpanjangan
            $perpanjangan = Perpanjangan::select('id_seniman','status')->whereRaw("BINARY id_perpanjangan = ?",[$request->input('id_perpanjangan')])->first();
            if (!$perpanjangan) {
                return response()->json(['status' => 'error', 'message' => 'Data Perpanjangan tidak ditemukan'], 400);
            }
            $statusDB = $perpanjangan->status;
            //check status
            if($ketInput ==  'proses' && ($statusDB == 'diterima' || $statusDB == 'ditolak')){
                return response()->json(['status' => 'error', 'message' => 'Data sudah diverifikasi'], 400);
            }
            if($statusDB ==  'diajukan' && ($ketInput == 'diterima' || $ketInput == 'ditolak')){
                return response()->json(['status' => 'error', 'message' => 'Data harus di proses'], 400);
            }
            if($ketInput ==  'ditolak' && $statusDB == 'diterima'){
                return response()->json(['status' => 'error', 'message' => 'Data sudah diverifikasi'], 400);
            }
            if($ketInput ==  'diterima' && $statusDB == 'ditolak'){
                return response()->json(['status' => 'error', 'message' => 'Data sudah diverifikasi'], 400);
            }
            if($ketInput == 'ditolak' && (empty($catatanInput) && is_null($catatanInput))){
                return response()->json(['status' => 'error', 'message' => 'Catatan harus di isi !'], 400);
            }

            if($ketInput == 'proses' || $ketInput == 'ditolak'){
                // Update perpanjangan status
                $updateQuery = Perpanjangan::whereRaw("BINARY id_perpanjangan = ?", [$request->input('id_perpanjangan')])
                ->update([
                    'status' => $ketInput == 'proses' ? 'proses' : ($ketInput == 'diterima' ? 'diterima' : 'ditolak'),
                    'catatan' => ($ketInput == 'proses' || $ketInput == 'diterima') ? '' : $catatanInput
                ]);
                if ($updateQuery <= 0) {
                    return response()->json(['status' => 'error', 'message' => 'Status gagal diubah'], 500);
                }
                return response()->json(['status' => 'success', 'message' => 'Status berhasil diubah']);
            }else if($ketInput == 'diterima'){
                //get nomor_induk, id_kategori
                $seniman = Seniman::select('nomor_induk','id_kategori_seniman')->whereRaw("BINARY id_seniman = ?",[$perpanjangan->id_seniman])->first();
                //add histori
                $ins = HistoriNis::insert([
                    'nis' => $seniman->nomor_induk,
                    'tahun' => substr($seniman->nomor_induk, strrpos($seniman->nomor_induk, '/') + 1),
                    'id_seniman' => $perpanjangan->id_seniman,
                ]);
                if(!$ins){
                    return response()->json(['status'=>'error','message'=>'Gagal menambahkan data histori nis'], 500);
                }
                // Update  seniman nomor_induk
                $updateQuery = Seniman::whereRaw("BINARY id_seniman = ?", [$perpanjangan->id_seniman])
                ->update([
                    'nomor_induk' => $this->generateNIS(['id_kategori'=>$seniman->id_kategori_seniman],'perpanjangan'),
                ]);
                if ($updateQuery <= 0) {
                    return response()->json(['status' => 'error', 'message' => 'Status gagal diubah'], 500);
                }
                //delete perpanjangan
                if (!Perpanjangan::where('id_perpanjangan',$request->input('id_perpanjangan'))->delete()) {
                    return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data Sewa Kategori Seniman'], 500);
                }
                return response()->json(['status' => 'success', 'message' => 'Status berhasil diubah']);
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson->message,
                );
            }
            return response()->json($responseData,isset($errorJson['code']) ? $errorJson['code'] : 400);
        }
    }
}