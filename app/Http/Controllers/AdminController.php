<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Auth\JwtController;
use App\Models\User;
use App\Models\Verify;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Exception;
class AdminController extends Controller
{
    public function getFotoAdmin(Request $request){
        $userAuth = $request->input('user_auth');
        $referrer = $request->headers->get('referer');
        if (!$referrer && $request->path() == 'public/download/foto') {
            abort(404);
        }
        if (empty($userAuth['foto']) || is_null($userAuth['foto'])) {
            $defaultPhotoPath = $userAuth['jenis_kelamin'] == 'laki-laki' ? 'admin/default_boy.jpg' : 'admin/default_girl.png';
            return response()->download(storage_path('app/' . $defaultPhotoPath), 'foto.' . pathinfo($defaultPhotoPath, PATHINFO_EXTENSION));
        } else {
            return response()->download(storage_path('app/admin/' . $userAuth['foto']), 'foto.' . $userAuth['foto']->extension());
        }
    }
    public function updateProfile(Request $request){
        $rules = [
            'email_new'=>'required|email',
            'nama_lengkap' => 'required|max:50',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telpon' => 'required|digits_between:13|integer',
            'tempat_lahir' => 'required|max:45',
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:' . now()->toDateString()],
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ];
        $messages = [
            'email_new.required'=>'Email wajib di isi',
            'email_new.email'=>'Email yang anda masukkan invalid',
            'nama_lengkap.required' => 'Nama peminjam wajib di isi',
            'nama_lengkap.max' => 'Nama peminjam maksimal 50 karakter',
            'jenis_kelamin.required' => 'Jenis kelamin wajib di isi',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
            'no_telpon.required' => 'Contact person wajib di isi',
            'no_telpon.digits_between' => 'Contact person tidak boleh lebih dari 13 karakter',
            'tempat_lahir.required' => 'Nama peminjam wajib di isi',
            'tempat_lahir.max' => 'Nama peminjam maksimal 45 karakter',
            'tanggal_lahir.required' => 'Tanggal lahir wajib di isi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tanggal_lahir.before_or_equal' => 'Tanggal Lahir harus Sebelum dari tanggal sekarang',
            'foto.required' => 'Foto Admin wajib di isi',
            'foto.image' => 'Foto Admin harus berupa gambar',
            'foto.mimes' => 'Format foto admin tidak valid. Gunakan format jpeg, png, jpg',
            'foto.max' => 'Ukuran foto admin tidak boleh lebih dari 5MB',
        ];
        if($request->input('user_auth')['role'] !== 'super admin'){
            $rules = array_merge($rules, [
                'role' => 'required|in:admin event, admin seniman, admin tempat',
            ]);
            $messages = array_merge($messages, [
                'role.required' => 'Role wajib di isi',
                'role.in' => 'Role tidak valid',
            ]);
        }
        $validator = Validator::make($request->only('email_new', 'nama_lengkap', 'jenis_kelamin', 'no_telpon', 'tempat_lahir', 'tanggal_lahir', $request->input('user_auth')['role'] !== 'super admin' ? 'role' : '', 'foto'), $rules, $messages);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check email
        $user = User::select('email')->whereRaw("BINARY email = ?",[$request->input('user_auth')['email']])->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Admin tidak ditemukan'], 400);
        }
        //update profile
        $updateProfile = $user->update([
            'email' => $request->input('email_new'),
            'nama_lengkap' => $request->input('nama_lengkap'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'no_telpon' => $request->input('no_telpon'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'role' => $request->input('role'),
            'foto' => $request->input('foto'),
            'updated_at'=> Carbon::now()
        ]);
        if (!$updateProfile) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui Profile'], 500);
        }
        //update cookie
        return response()->json(['status' =>'success','message'=>'Profile berhasil di perbarui']);
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