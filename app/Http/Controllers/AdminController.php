<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Auth\JwtController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\RefreshToken;
use Carbon\Carbon;
use Closure;
class AdminController extends Controller
{
    public function getFotoProfile(Request $request){
        $userAuth = $request->input('user_auth');
        $referrer = $request->headers->get('referer');
        if (!$referrer && $request->path() == 'public/download/foto') {
            abort(404);
        }
        if (empty($userAuth['foto']) || is_null($userAuth['foto'])) {
            $defaultPhotoPath = $userAuth['jenis_kelamin'] == 'laki-laki' ? 'admin/default_boy.jpg' : 'admin/default_girl.png';
            return response()->download(storage_path('app/' . $defaultPhotoPath), 'foto.' . pathinfo($defaultPhotoPath, PATHINFO_EXTENSION));
        } else {
            $filePath = storage_path('app/admin/foto/' . $userAuth['foto']);
            if (file_exists($filePath)) {
                return response(Crypt::decrypt(file_get_contents($filePath)));
            }
            abort(404);
        }
    }
    public function getFotoAdmin(Request $request, $userID){
        $referrer = $request->headers->get('referer');
        if (!$referrer && $request->path() == 'public/download/foto') {
            abort(404);
        }
        $admin = User::select('jenis_kelamin','foto')->where('id_user',$userID)->limit(1)->get()[0];
        if (!$admin) {
            return response()->json(['status' => 'error', 'message' => 'Data Admin tidak ditemukan'], 404);
        }
        if (empty($admin->foto) || is_null($admin->foto)) {
            $defaultPhotoPath = $admin->jenis_kelamin == 'laki-laki' ? 'admin/default_boy.jpg' : 'admin/default_girl.png';
            return response()->download(storage_path('app/' . $defaultPhotoPath), 'foto.' . pathinfo($defaultPhotoPath, PATHINFO_EXTENSION));
        } else {
            $filePath = storage_path('app/admin/foto/' . $admin['foto']);
            if (file_exists($filePath)) {
                return response(Crypt::decrypt(file_get_contents($filePath)));
            }
            abort(404);
        }
    }
    public function tambahAdmin(Request $request){
        $validator = Validator::make($request->only('email_new','nama_lengkap','jenis_kelamin','no_telpon','tempat_lahir','tanggal_lahir','role','password','foto'), [
            'email_new'=>'required|email',
            'nama_lengkap' => 'required|max:50',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telpon' => 'required|digits_between:11,13',
            'tempat_lahir' => 'required|max:45',
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:' . now()->toDateString()],
            'role' => 'required|in:admin event,admin seniman,admin tempat',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:25',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\p{P}\p{S}])[\p{L}\p{N}\p{P}\p{S}]+$/u',
            ],
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ],[
            'email_new.required'=>'Email wajib di isi',
            'email_new.email'=>'Email yang anda masukkan invalid',
            'nama_lengkap.required' => 'Nama admin wajib di isi',
            'nama_lengkap.max' => 'Nama admin maksimal 50 karakter',
            'jenis_kelamin.required' => 'Jenis kelamin wajib di isi',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
            'no_telpon.required' => 'Nomor telepon wajib di isi',
            'no_telpon.digits_between' => 'Nomor telepon tidak boleh lebih dari 13 karakter',
            'tempat_lahir.required' => 'Nama admin wajib di isi',
            'tempat_lahir.max' => 'Nama admin maksimal 45 karakter',
            'tanggal_lahir.required' => 'Tanggal lahir wajib di isi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tanggal_lahir.before_or_equal' => 'Tanggal Lahir harus Sebelum dari tanggal sekarang',
            'role.required' => 'Role admin wajib di isi',
            'role.in' => 'Role admin tidak valid',
            'password.required'=>'Password wajib di isi',
            'password.min'=>'Password minimal 8 karakter',
            'password.max'=>'Password maksimal 25 karakter',
            'password.regex'=>'Password terdiri dari 1 huruf besar, huruf kecil, angka dan karakter unik',
            'foto.required' => 'Foto Admin wajib di isi',
            'foto.image' => 'Foto Admin harus berupa gambar',
            'foto.mimes' => 'Format foto admin tidak valid. Gunakan format jpeg, png, jpg',
            'foto.max' => 'Ukuran foto admin tidak boleh lebih dari 5MB',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check email
        if (User::select("email")->whereRaw("BINARY email = ?",[$request->input('email_new')])->limit(1)->exists()){
            return response()->json(['status'=>'error','message'=>'Email sudah digunakan'],400);
        }
        //process file foto
        if (!$request->hasFile('foto')) {
            return response()->json(['status'=>'error','message'=>'Foto wajib di isi'], 400);
        }
        $file = $request->file('foto');
        if(!($file->isValid() && in_array($file->extension(), ['pdf', 'jpeg', 'png', 'jpg']))){
            return response()->json(['status'=>'error','message'=>'Format Foto tidak valid. Gunakan format jpeg, png, jpg'], 400);
        }
        $fotoName = $file->hashName();
        $fileData = Crypt::encrypt(file_get_contents($file));
        Storage::disk('admin')->put('foto/' . $fotoName, $fileData);
        $ins = User::insert([
            'nama_lengkap'=>$request->input('nama_lengkap'),
            'no_telpon'=>$request->input('no_telpon'),
            'jenis_kelamin'=>$request->input('jenis_kelamin'),
            'tanggal_lahir'=>$request->input('tanggal_lahir'),
            // 'tanggal_lahir'=>Carbon::createFromFormat('d-m-Y', $request->input('tanggal_lahir'))->format('Y-m-d'),
            'tempat_lahir'=>$request->input('tempat_lahir'),
            'role'=>$request->input('role'),
            'email'=>$request->input('email'),
            'password'=>Hash::make($request->input('password')),
            'foto'=>$fotoName,
            'verifikasi'=>true
        ]);
        if(!$ins){
            return response()->json(['status'=>'error','message'=>'Gagal menambahkan data Admin'], 500);
        }
        return response()->json(['status'=>'success','message'=>'Data Admin berhasil ditambahkan']);
    }
    public function editAdmin(Request $request){
        $validator = Validator::make($request->only('email_admin', 'email_new','nama_lengkap','jenis_kelamin','no_telpon','tempat_lahir','tanggal_lahir','role','password','foto'), [
            'email_admin'=>'required|email',
            'email_new'=>'required|email',
            'nama_lengkap' => 'required|max:50',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telpon' => 'required|digits_between:11,13',
            'tempat_lahir' => 'required|max:45',
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:' . now()->toDateString()],
            'role' => 'required|in:admin event,admin seniman,admin tempat',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:25',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\p{P}\p{S}])[\p{L}\p{N}\p{P}\p{S}]+$/u',
            ],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ],[
            'email_admin.required'=>'Email wajib di isi',
            'email_admin.email'=>'Email yang anda masukkan invalid',
            'email_new.required'=>'Email wajib di isi',
            'email_new.email'=>'Email yang anda masukkan invalid',
            'nama_lengkap.required' => 'Nama admin wajib di isi',
            'nama_lengkap.max' => 'Nama admin maksimal 50 karakter',
            'jenis_kelamin.required' => 'Jenis kelamin wajib di isi',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
            'no_telpon.required' => 'Nomor telepon wajib di isi',
            'no_telpon.digits_between' => 'Nomor telepon tidak boleh lebih dari 13 karakter',
            'tempat_lahir.required' => 'Nama admin wajib di isi',
            'tempat_lahir.max' => 'Nama admin maksimal 45 karakter',
            'tanggal_lahir.required' => 'Tanggal lahir wajib di isi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tanggal_lahir.before_or_equal' => 'Tanggal Lahir harus Sebelum dari tanggal sekarang',
            'role.required' => 'Role admin wajib di isi',
            'role.in' => 'Role admin tidak valid',
            'password.min'=>'Password minimal 8 karakter',
            'password.max'=>'Password maksimal 25 karakter',
            'password.regex'=>'Password terdiri dari 1 huruf besar, huruf kecil, angka dan karakter unik',
            'foto.image' => 'Foto Admin harus berupa gambar',
            'foto.mimes' => 'Format foto admin tidak valid. Gunakan format jpeg, png, jpg',
            'foto.max' => 'Ukuran foto admin tidak boleh lebih dari 5MB',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check data admin
        $admin = User::select('password','foto')->whereRaw("BINARY email = ?",[$request->input('email_admin')])->limit(1)->get()[0];
        if (!$admin) {
            return response()->json(['status' => 'error', 'message' => 'Data Admin tidak ditemukan'], 404);
        }
        //process file foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            if(!($file->isValid() && in_array($file->extension(), ['jpeg', 'png', 'jpg']))){
                return response()->json(['status'=>'error','message'=>'Format Foto tidak valid. Gunakan format jpeg, png, jpg'], 400);
            }
            $destinationPath = storage_path('app/admin/');
            $fileToDelete = $destinationPath . $admin['foto'];
            if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
                unlink($fileToDelete);
            }
            Storage::disk('admin')->delete('foto/'.$admin['foto']);
            $fotoName = $file->hashName();
            $fileData = Crypt::encrypt(file_get_contents($file));
            Storage::disk('admin')->put('foto/' . $fotoName, $fileData);
        }
        //update admin
        $updatedAdmin = User::whereRaw("BINARY email = ?",[$request->input('email_admin')])->update([
            'email'=>$request->input('email_new'),
            'nama_lengkap'=>$request->input('nama_lengkap'),
            'no_telpon'=>$request->input('no_telpon'),
            'jenis_kelamin'=>$request->input('jenis_kelamin'),
            'tanggal_lahir'=>$request->input('tanggal_lahir'),
            // 'tanggal_lahir'=>Carbon::createFromFormat('d-m-Y', $request->input('tanggal_lahir'))->format('Y-m-d'),
            'tempat_lahir'=>$request->input('tempat_lahir'),
            'role'=>$request->input('role'),
            'password'=> (empty($request->input('password')) || is_null($request->input('password'))) ? $admin['password']: Hash::make($request->input('password')),
            'foto' => $request->hasFile('foto') ? $fotoName : $admin['foto'],
            'verifikasi'=>true
        ]);
        if (!$updatedAdmin) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui data Seniman'], 500);
        }
        return response()->json(['status'=>'success','message'=>'Data Admin berhasil diperbarui']);
    }
    public function updateProfile(Request $request){
        $userAuth = $request->input('user_auth');
        $validator = Validator::make($request->only('email_new', 'nama_lengkap', 'jenis_kelamin', 'no_telpon', 'tempat_lahir', 'tanggal_lahir', $userAuth['role'] !== 'super admin' ? 'role' : null, 'foto'),
            [
                'email_new'=>'nullable|email',
                'nama_lengkap' => 'required|max:50',
                'jenis_kelamin' => 'required|in:laki-laki,perempuan',
                'no_telpon' => 'required|digits_between:11,13',
                'tempat_lahir' => 'required|max:45',
                'tanggal_lahir' => ['required', 'date', 'before_or_equal:' . now()->toDateString()],
                function($attribute, $value, $parameters, $validator) use ($userAuth){
                    if($userAuth['role'] !== 'super admin'){
                        return ['role' => 'required|in:admin event, admin seniman, admin tempat'];
                    }
                    return [];
                },
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ],[
                'email_new.email'=>'Email yang anda masukkan invalid',
                'nama_lengkap.required' => 'Nama admin wajib di isi',
                'nama_lengkap.max' => 'Nama admin maksimal 50 karakter',
                'jenis_kelamin.required' => 'Jenis kelamin wajib di isi',
                'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
                'no_telpon.required' => 'Nomor telepon wajib di isi',
                'no_telpon.digits_between' => 'Nomor telepon tidak boleh lebih dari 13 karakter',
                'tempat_lahir.required' => 'Nama admin wajib di isi',
                'tempat_lahir.max' => 'Nama admin maksimal 45 karakter',
                'tanggal_lahir.required' => 'Tanggal lahir wajib di isi',
                'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
                'tanggal_lahir.before_or_equal' => 'Tanggal Lahir harus Sebelum dari tanggal sekarang',
                function($attribute, $value, $parameters, $validator) use ($userAuth){
                    if($userAuth['role'] !== 'super admin'){
                        return [
                            'role.required' => 'Role wajib di isi',
                            'role.in' => 'Role tidak valid'
                        ];
                    }
                    return [];
                },
                'foto.required' => 'Foto Admin wajib di isi',
                'foto.image' => 'Foto Admin harus berupa gambar',
                'foto.mimes' => 'Format foto admin tidak valid. Gunakan format jpeg, png, jpg',
                'foto.max' => 'Ukuran foto admin tidak boleh lebih dari 5MB',
            ],
        );
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check email
        $user = User::select('email','foto')->whereRaw("BINARY email = ?",[$request->input('user_auth')['email']])->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Admin tidak ditemukan'], 400);
        }
        //process file foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            if(!($file->isValid() && in_array($file->extension(), ['jpeg', 'png', 'jpg']))){
                return response()->json(['status'=>'error','message'=>'Format Foto tidak valid. Gunakan format jpeg, png, jpg'], 400);
            }
            $destinationPath = storage_path('app/admin/');
            $fileToDelete = $destinationPath . $user['foto'];
            if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
                unlink($fileToDelete);
            }
            Storage::disk('admin')->delete('foto/'.$user['foto']);
            $fotoName = $file->hashName();
            $fileData = Crypt::encrypt(file_get_contents($file));
            Storage::disk('admin')->put('foto/' . $fotoName, $fileData);
        }
        //update profile
        $updateProfile = User::whereRaw("BINARY email = ?",[$request->input('user_auth')['email']])->update([
            'email'=> (empty($request->input('email_new')) || is_null($request->input('email_new'))) ? $request->input('user_auth')['email'] : $request->input('email_new'),
            'nama_lengkap' => $request->input('nama_lengkap'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'no_telpon' => $request->input('no_telpon'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'role' => $userAuth['role'] !== 'super admin' ? $request->input('role') : $userAuth['role'],
            'foto' => $request->hasFile('foto') ? $fotoName : $user['foto'],
            'updated_at'=> Carbon::now()
        ]);
        if (!$updateProfile) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui Profile'], 500);
        }
        //update cookie
        $jwtController = new JWTController();
        $data = $jwtController->createJWTWebsite($request->input('email_new'),new RefreshToken());
        if(is_null($data)){
            return response()->json(['status'=>'error','message'=>'create token error'],500);
        }else{
            if($data['status'] == 'error'){
                return response()->json(['status'=>'error','message'=>$data['message']],400);
            }else{
                $data1 = ['email'=>$request->input('email_new'),'number'=>$data['number']];
                $encoded = base64_encode(json_encode($data1));
                return response()->json(['status'=>'success','message'=>'Profile Anda berhasil di perbarui'])
                ->cookie('token1',$encoded,time()+intval(env('JWT_ACCESS_TOKEN_EXPIRED')))
                ->cookie('token2',$data['data']['token'],time() + intval(env('JWT_ACCESS_TOKEN_EXPIRED')))
                ->cookie('token3',$data['data']['refresh'],time() + intval(env('JWT_REFRESH_TOKEN_EXPIRED')));
            }
        }
    }
    //update from profile
    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'password_old' => [
                'required',
                'string',
                'min:8',
                'max:25',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\p{P}\p{S}])[\p{L}\p{N}\p{P}\p{S}]+$/u',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:25',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\p{P}\p{S}])[\p{L}\p{N}\p{P}\p{S}]+$/u',
            ],
            'password_confirm' => [
                'required',
                'string',
                'min:8',
                'max:25',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\p{P}\p{S}])[\p{L}\p{N}\p{P}\p{S}]+$/u',
            ],
        ],[
            'email.email'=>'Email yang anda masukkan invalid',
            'password.required'=>'Password wajib di isi',
            'password.min'=>'Password minimal 8 karakter',
            'password.max'=>'Password maksimal 25 karakter',
            'password.regex'=>'Password terdiri dari 1 huruf besar, huruf kecil, angka dan karakter unik',
            'password_confirm.required'=>'Password konfirmasi harus di isi',
            'password_confirm.min'=>'Password konfirmasi minimal 8 karakter',
            'password_confirm.max'=>'Password konfirmasi maksimal 25 karakter',
            'password_confirm.regex'=>'Password konfirmasi terdiri dari 1 huruf besar, huruf kecil, angka dan karakter unik',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $passOld = $request->input('password_old');
        $pass = $request->input('password');
        $passConfirm = $request->input('password_confirm');
        if($pass !== $passConfirm){
            return response()->json(['status'=>'error','message'=>'Password Harus Sama'],400);
        }
        //check email
        $user = User::select('password')->whereRaw("BINARY email = ?",[$request->input('user_auth')['email']])->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Admin tidak ditemukan'], 400);
        }
        if(!password_verify($passOld,$user->password)){
            return response()->json(['status'=>'error','message'=>'Password salah'],400);
        }
        //update password
        $updatePassword = User::whereRaw("BINARY email = ?",[$request->input('user_auth')['email']])->update([
            'password'=>Hash::make($pass),
            'updated_at'=> Carbon::now()
        ]);
        if (!$updatePassword) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui password admin'], 500);
        }
        return response()->json(['status' =>'success','message'=>'Password Admin berhasil di perbarui']);
    }
    public function hapusAdmin(Request $request){
        $validator = Validator::make($request->only('emailID'), [
            'emailID' => 'required',
        ], [
            'emailID.required' => 'Email ID wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check data Admin
        $admin = User::select('foto')->find($request->input('emailID'));
        if (!$admin) {
            return response()->json(['status' => 'error', 'message' => 'Data Admin tidak ditemukan'], 404);
        }
        //delete foto
        $destinationPath = storage_path('app/admin/');
        $fileToDelete = $destinationPath . $admin->foto;
        if (file_exists($fileToDelete) && !is_dir($fileToDelete)) {
            unlink($fileToDelete);
        }
        Storage::disk('admin')->delete('foto/'.$admin->foto);

        if (!User::where('id_user',$request->input('emailID'))->delete()) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data Admin'], 500);
        }
        return response()->json(['status' => 'success', 'message' => 'Data Admin berhasil dihapus']);
    }
    public function logout(Request $request, JWTController $jwtController){
        $email = $request->input('email');
        $number = $request->input('number');
        if(empty($email) || is_null($email)){
            return response()->json(['status'=>'error','message'=>'email empty'],400);
        }else if(empty($number) || is_null($number)){
            return response()->json(['status'=>'error','message'=>'token empty'],400);
        }else{
            $deleted = $jwtController->deleteRefreshWebsite($email,$number);
            if($deleted['status'] == 'error'){
                return redirect("/login")->withCookies([Cookie::forget('token1'),Cookie::forget('token2'), Cookie::forget('token3')]);
                // return response()->json(['status'=>'error',$deleted['message']],$deleted['code']);
            }else{
                return redirect("/login")->withCookies([Cookie::forget('token1'),Cookie::forget('token2'), Cookie::forget('token3')]);
            }
        }
    }
}
?>