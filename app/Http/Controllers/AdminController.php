<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Auth\JwtController;
use App\http\controllers\Mail\MailController;
use App\Http\Controllers\Website\ChangePasswordController;
use App\Http\Controllers\Website\NotificationPageController;
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
    public function getChangePass(Request $request, User $user, $any = null){
        $changePassPage = new ChangePasswordController();
        $notificationPage = new NotificationPageController();
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'code' =>'nullable'
        ],[
            'email.required'=>'Email wajib di isi',
            'email.email'=>'Email yang anda masukkan invalid',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors = $errorMessages[0];
            }
            return response()->json(['status' => 'error', 'message' => $errors], 400);
        }
        $email = $request->input('email');
        $code = $request->input('code');
        if(Str::startsWith($request->path(), 'verify/password') && $request->isMethod('get')){
            $email = $request->query('email');
            if(Verify::whereRaw("BINARY link = ?",[$any])->limit(1)->exists()){
                if(Verify::whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
                    if(Verify::whereRaw("BINARY email = ? AND BINARY link = ?",[$email,$any])->limit(1)->exists()){
                        $currentDateTime = Carbon::now();
                        if (DB::table('verify')->whereRaw("BINARY email = ?",[$email])->where('updated_at', '>=', $currentDateTime->subMinutes(1))->exists()) {
                            // return $changePassPage->showCreatePass($request);
                            return view('page.forgotPassword',['email'=>$email, 'title'=>'Reset Password','link'=>$any,'code'=>'','div'=>'verifyDiv','description'=>'changePass']);
                        }else{
                            $deleted = DB::table('verify')->whereRaw("BINARY email = ? AND description = 'changePass'",[$email])->delete();
                            return $notificationPage->showFailResetPass($request,'Link Expired');
                            // return response()->json(['status'=>'error','message'=>'link expired'],400);
                        }
                    }else{
                        return $notificationPage->showFailResetPass($request,'Link invalid');
                        // return response()->json(['status'=>'error','message'=>'link invalid'],400);
                    }
                }else{
                    return $notificationPage->showFailResetPass($request,'Email invalid');
                    // return response()->json(['status'=>'error','message'=>'email invalid'],400);
                }
            }else{
                return $notificationPage->showFailResetPass($request,'Link invalid');
                // return response()->json(['status'=>'error','message'=>'link invalid'],400);
            }
        }else{
            if(Verify::whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
                if(Verify::whereRaw("BINARY email = ? AND BINARY code = ?",[$email,$code])->limit(1)->exists()){
                    $currentDateTime = Carbon::now();
                    if (DB::table('verify')->whereRaw("BINARY email = ?",[$email])->where('updated_at', '>=', $currentDateTime->subMinutes(15))->exists()) {
                        return response()->json(['status'=>'success','message'=>'otp anda benar silahkan ganti password']);
                        // return response()->json(['status'=>'success','data'=>['div'=>'verify','description'=>'changePass']]);
                    }else{
                        $deleted = DB::table('verify')->whereRaw("BINARY email = ? AND description = 'changePass'",[$email])->delete();
                        return response()->json(['status'=>'error','message'=>'code otp expired'],400);
                    }
                }else{
                    return response()->json(['status'=>'error','message'=>'code otp invalid'],400);
                }
            }else{
                return response()->json(['status'=>'error','message'=>'email invalid'],400);
            }
        }
    }
    public function changePassEmail(Request $request, User $user, JWTController $jwtController, RefreshToken $refreshToken){
        // return response()->json('data kosongg');
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'nama'=>'nullable',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:25',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'password_confirm' => [
                'required',
                'string',
                'min:8',
                'max:25',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'code' => 'nullable',
            'link' => 'nullable',
            'description'=>'required'
        ],[
            'email.required'=>'Email wajib di isi',
            'email.email'=>'Email yang anda masukkan invalid',
            'password.required'=>'Password wajib di isi',
            'password.min'=>'Password minimal 8 karakter',
            'password.max'=>'Password maksimal 25 karakter',
            'password.regex'=>'Password baru wajib terdiri dari 1 huruf besar, huruf kecil, angka dan karakter unik',
            'password_confirm.required'=>'Password konfirmasi konfirmasi harus di isi',
            'password_confirm.min'=>'Password konfirmasi minimal 8 karakter',
            'password_confirm.max'=>'Password konfirmasi maksimal 25 karakter',
            'password_confirm.regex'=>'Password konfirmasi terdiri dari 1 huruf besar, huruf kecil, angka dan karakter unik',
            'description.required'=>'Deskripsi wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors = $errorMessages[0];
            }
            return response()->json(['status' => 'error', 'message' => $errors], 400);
        }
        $email = $request->input('email');
        $pass = $request->input("password");
        $pass1 = $request->input("password_confirm");
        $code = $request->input('code');
        $link = $request->input('link');
        $desc = $request->input('description');
        if($pass !== $pass1){
            return response()->json(['status'=>'error','message'=>'Password Harus Sama'],400);
        }else{
            if(is_null($link) || empty($link)){
                if($desc == 'createUser'){
                    $user->email = $request->input('email');
                    $user->nama = $request->input('nama');
                    $user->password = Hash::make($request->input('password'));
                    $user->email_verified = true;
                    $user->level = 'ADMIN';
                    if($user->save()){
                        $data = $jwtController->createJWTWebsite($email,$refreshToken);
                        if(is_null($data)){
                            return response()->json(['status'=>'error','message'=>'create token error'],500);
                        }else{
                            if($data['status'] == 'error'){
                                return response()->json(['status'=>'error','message'=>$data['message']],400);
                            }else{
                                $data1 = ['email'=>$email,'number'=>$data['number']];
                                $encoded = base64_encode(json_encode($data1));
                                return response()->json(['status'=>'success','message'=>'login sukses silahkan masuk dashboard'])
                                ->cookie('token1',$encoded,time()+intval(env('JWT_REFRESH_TOKEN_EXPIRED')),'/','',true)
                                ->cookie('token2',$data['data']['token'],time() + intval(env('JWT_ACCESS_TOKEN_EXPIRED')),'/','',true)
                                ->cookie('token3',$data['data']['refresh'],time() + intval(env('JWT_REFRESH_TOKEN_EXPIRED')),'/','',true);
                            }
                        }
                    }else{
                        return ['status'=>'error','message'=>'Akun Gagal Dibuat'];
                    }
                }else{
                    if(Verify::select("email")->whereRaw("BINARY code = ?",[$code])->limit(1)->exists()){
                        if(User::select("email")->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
                            if(Verify::select("email")->whereRaw("BINARY email = ? AND BINARY code = ?",[$email,$code])->limit(1)->exists()){
                                $currentDateTime = Carbon::now();
                                if (DB::table('verify')->whereRaw("BINARY email = ?",[$email])->where('updated_at', '>=', $currentDateTime->subMinutes(15))->exists()) {
                                    if(is_null(DB::table('users')->whereRaw("BINARY email = ?",[$email])->update(['password'=>Hash::make($pass)]))){
                                        return response()->json(['status'=>'error','message'=>'error update password'],500);
                                    }else{
                                        $deleted = DB::table('verify')->whereRaw("BINARY email = ?",[$email])->delete();
                                        if($deleted){
                                            return response()->json(['status'=>'success','message'=>'ganti password berhasil silahkan login']);
                                        }else{
                                            return response()->json(['status'=>'error','message'=>'error update password'],500);
                                        }
                                    }
                                }else{
                                    $deleted = DB::table('verify')->whereRaw("BINARY email = ? AND description = 'changePass'",[$email])->delete();
                                    return response()->json(['status'=>'error','message'=>'token expired'],400);
                                }
                            }else{
                                return response()->json(['status'=>'error','message'=>'Invalid Email'],400);
                            }
                        }else{
                            return response()->json(['status'=>'error','message'=>'Invalid Email'],400);
                        }
                    }else{
                        return response()->json(['status'=>'error','message'=>'token invalid'],400);
                    }
                }
            }else{
                if(Verify::select("email")->whereRaw("BINARY link = ? AND description = '$desc'",[$link])->limit(1)->exists()){
                    if(User::select("email")->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
                        if(Verify::select("email")->whereRaw("BINARY email = ? AND BINARY link = ? AND description = '$desc'",[$email,$link])->limit(1)->exists()){
                            $currentDateTime = Carbon::now();
                            if (DB::table('verify')->whereRaw("BINARY email = ?",[$email])->where('updated_at', '>=', $currentDateTime->subMinutes(15))->exists()) {
                                if(is_null(DB::table('users')->whereRaw("BINARY email = ?",[$email])->update(['password'=>Hash::make($pass)]))){
                                    return response()->json(['status'=>'error','message'=>'error update password'],500);
                                }else{
                                    $deleted = DB::table('verify')->whereRaw("BINARY email = ? AND description = '$desc'",[$email])->delete();
                                    if($deleted){
                                        return response()->json(['status'=>'success','message'=>'ganti password berhasil silahkan login']);
                                    }else{
                                        return response()->json(['status'=>'error','message'=>'error update password'],500);
                                    }
                                }
                            }else{
                                $deleted = DB::table('verify')->whereRaw("BINARY email = ? AND description = 'changePass'",[$email])->delete();
                                return response()->json(['status'=>'error','message'=>'link expired'],400);
                            }
                        }else{
                            return response()->json(['status'=>'error','message'=>'Email invalid'],400);
                        }
                    }else{
                        return response()->json(['status'=>'error','message'=>'Invalid Email1'],400);
                    }
                }else{
                    return response()->json(['status'=>'error','message'=>'link expired'],400);
                }
            }
        }
    }
    public function createUser(Request $request, MailController $mailController, User $user, Verify $verify){
        $validator = Validator::make($request->all(), [
            'email'=>'required | email',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:25',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\p{P}\p{S}])[\p{L}\p{N}\p{P}\p{S}]+$/u',
            ],
            'nama'=>'required',
        ],[
            'nama.required'=>'nama wajib di isi',
            'email.required'=>'Email wajib di isi',
            'email.email'=>'Email yang anda masukkan invalid',
            'password.required'=>'Password wajib di isi',
            'password.min'=>'Password minimal 8 karakter',
            'password.max'=>'Password maksimal 25 karakter',
            'password.regex'=>'Password baru wajib terdiri dari 1 huruf besar, huruf kecil, angka dan karakter unik',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors = $errorMessages[0];
            }
            return response()->json(['status' => 'error', 'message' => $errors], 400);
        }
        $user->email = $request->input('email');
        $user->nama = $request->input('nama');
        $user->password = Hash::make($request->input('password'));
        $user->email_verified = false;
        if($user->save()){
            $email = $mailController->createVerifyEmail($request,$verify);
            if($email['status'] == 'error'){
                return ['status'=>'error','message'=>$email['message']];
            }else{
                
                return ['status'=>'success','message'=>$email['message'],'data'=>$email['data']];
            }
        }else{
            return ['status'=>'error','message'=>'Akun Gagal Dibuat'];
        }
    }
    public function getVerifyEmail(Request $request, User $user, $any = null){
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'link' => 'nullable',
        ],[
            'email.required'=>'Email wajib di isi',
            'email.email'=>'Email yang anda masukkan invalid',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors = $errorMessages[0];
            }
            return response()->json(['status' => 'error', 'message' => $errors], 400);
        }
        $email = $request->input('email');
        if(!User::select("email")->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
            return response()->json(['status'=>'error','message'=>'Email invalid']);
        }else{
            if($request->path() === '/verify/email/*' && $request->isMethod("get")){
                if(Verify::select("link")->whereRaw("BINARY link = ?",[$any])->limit(1)->exists()){
                    return view('page.verifyEmail');
                }else{
                    return response()->json(['status'=>'error','message'=>'invalid token'],400);
                }
            }
        }
    }
    public function verifyEmail(Request $request, User $user, $any = null){
        $notificationPage = new NotificationPageController();
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'code' =>'nullable'
        ],[
            'email.required'=>'Email wajib di isi',
            'email.email'=>'Email yang anda masukkan invalid',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors = $errorMessages[0]; 
            }
            return response()->json(['status' => 'error', 'message' => $errors], 400);
        }
        $email = $request->input('email');
        $code = $request->input('code');
        if(Str::startsWith($request->path(), 'verify/email') && $request->isMethod('get')){
            $email = $request->query('email');
            if(Verify::select("link")->whereRaw("BINARY link = ?",[$any])->limit(1)->exists()){
                if(Verify::select("email")->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
                    if(Verify::select("code")->whereRaw("BINARY email = ? AND BINARY link = ? AND description = 'verifyEmail'",[$email,$any])->limit(1)->exists()){
                        $currentDateTime = Carbon::now();
                        if (DB::table('verify')->whereRaw("BINARY email = ?",[$email])->where('updated_at', '>=', $currentDateTime->subMinutes(15))->exists()) {
                            if(is_null(DB::table('users')->whereRaw("BINARY email = ?",[$email])->update(['email_verified'=>true]))){
                                return response()->json(['status'=>'error','message'=>'error verify email'],500);
                            }else{
                                $deleted = DB::table('verify')->whereRaw("BINARY email = ?",[$email])->delete();
                                if($deleted){
                                    return $notificationPage->showSuccessVerifyEmail($request, 'Verifikasi email berhasil silahkan login');
                                }else{
                                    return $notificationPage->showFailVerifyEmail($request,'Error verifikasi Email',500);
                                }
                            }
                        }else{
                            $deleted = DB::table('verify')->whereRaw("BINARY email = ? AND description = 'verifyEmail'",[$email])->delete();
                            return $notificationPage->showFailVerifyEmail($request,'Link Expired');
                            // return response()->json(['status'=>'error','message'=>'link expired'],400);
                        }
                    }else{
                        return $notificationPage->showFailVerifyEmail($request,'Link invalid');
                        // return response()->json(['status'=>'error','message'=>'link invalid'],400);
                    }
                }else{
                    return $notificationPage->showFailVerifyEmail($request,'email invalid');
                    // return response()->json(['status'=>'error','message'=>'email invalid'],400);
                }
            }else{
                return $notificationPage->showFailVerifyEmail($request,'Link invalid');
                // return response()->json(['status'=>'error','message'=>'link invalid'],400);
            }
        }else{
            if(Verify::select("email")->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
                if(Verify::select("code")->whereRaw("BINARY email = ? AND BINARY code = ? AND description = 'verifyEmail'",[$email, $code])->limit(1)->exists()){
                    $currentDateTime = Carbon::now();
                    if (DB::table('verify')->whereRaw("BINARY email = ?",[$email])->where('updated_at', '>=', $currentDateTime->subMinutes(15))->exists()) {
                        if(is_null(DB::table('users')->whereRaw("BINARY email = ?",[$email])->update(['email_verified'=>true]))){
                            return response()->json(['status'=>'error','message'=>'error update password'],500);
                        }else{
                            $deleted = DB::table('verify')->whereRaw("BINARY email = ?",[$email])->delete();
                            if($deleted){
                                return response()->json(['status'=>'success','message'=>'verifikasi email berhasil silahkan login']);
                            }else{
                                // return $notificationPage->showFailVerifyEmail($request,'error verifiy email',500);
                                return response()->json(['status'=>'error','message'=>'error verify email'],500);
                            }
                        }
                    }else{
                        $deleted = DB::table('verify')->whereRaw("BINARY email = ? AND description = 'verifyEmail'", [$email])->delete();
                        return response()->json(['status'=>'error','message'=>'token expired'],400);
                    }
                }else{
                    return response()->json(['status'=>'error','message'=>'token invalid'],400);
                }
            }else{
                return response()->json(['status'=>'error','message'=>'email invalid'],400);
            }
        }
    }
    public function updateUser(Request $request, Response $response, User $user){
        //
    }
    public function isExistUser($email){
        if(empty($email) || is_null($email)){
            return ['status'=>'error','message'=>'email empty'];
        }else{
            return ['status'=>'success','data'=>User::select('email')->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()];
        }
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