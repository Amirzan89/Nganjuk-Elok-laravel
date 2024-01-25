<?php
namespace App\Http\Controllers\Mobile\Services;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mobile\MasyarakatController;
use App\Http\Controllers\Website\NotificationPageController;
use App\Models\User;
use App\Models\RefreshToken;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\ForgotPassword;
use App\Mail\VerifyEmail;
use Carbon\Carbon;
use Exception;
class MailController extends Controller
{
    public function getVerifyEmail(Request $request){
        $email = $request->input('email');
        if(empty($email) || is_null($email)){
            return response()->json(['status'=>'error','message'=>'email empty'],404);
        }else{
            if(User::select("email")->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
                if(Verifikasi::select("email")->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
                    $dataDb = Verifikasi::select()->whereRaw("BINARY email = ?",[$email])->limit(1)->get();
                    $data = json_decode(json_encode($dataDb));
                    $code = $data['code'];
                    $linkPath = $data['link'];
                    $verificationLink = URL::to('/verify/' . $linkPath);
                    return response()->json(['status'=>'success','data'=>['code'=>$code,'link'=>$verificationLink]]);
                }else{
                    return response()->json(['status'=>'error','message'=>'email invalid'],404);
                }
            }else{
                return response()->json(['status'=>'error','message'=>'email invalid'],404);
            }
        }
    }
    public function createVerifyEmail(Request $request, Verifikasi $verify){
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
            return ['status' => 'error', 'message' => $errors,'code' => 400];
        }
        $userController = new MasyarakatController();
        $email = $request->input('email');
        //check email on table user
        $user = User::select('nama_lengkap')->whereRaw("BINARY email = ?",[$request->input('email')])->limit(1)->get()[0];
        if (!$user) {
            if($request->path() === 'verify/create/email' && $request->isMethod("get")){
                return ['status'=>'error','message'=>'email invalid'];
            }else{
                return ['status'=>'error','message'=>'email invalid','code'=>400];
            }
        }
        //check if user have create verify email
        $verify = Verifikasi::select('send','updated_at')->whereRaw("BINARY email = ?",[$request->input('email')])->where('deskripsi', 'email')->limit(1)->get()[0];
        if (!$verify) {
            $verificationCode = mt_rand(100000, 999999);
            $linkPath = Str::random(50);
            $verificationLink = URL::to('/verify/email/'.$linkPath);
            $verify->email = $email;
            $verify->code = $verificationCode;
            $verify->link = $linkPath;
            $verify->description = 'email';
            $verify->send = 1;
            if(!$verify->save()){
                return ['status'=>'error','message'=>'fail create verify email','code'=>400];
            }
            $data = ['name'=>$user->nama_lengkap,'email'=>$email,'code'=>$verificationCode,'link'=>urldecode($verificationLink)];
            Mail::to($email)->send(new VerifyEmail($data));
            return ['status'=>'Success','message'=>'Akun Berhasil Dibuat Silahkan verifikasi email','code'=>200,'data'=>['waktu'=>Carbon::now()->addMinutes($userController->conditionOTP[0])]];
        }
        //checking if user have create verify email
        $expTime = $userController->conditionOTP[($verify->send - 1)];
        if (!Carbon::parse($verify->updated_at)->diffInMinutes(Carbon::now()) <= $expTime) {
            return ['status'=>'error','message'=>'Kami sudah mengirim email verifikasi ','data'=>true];
        }
        //if after desired time then update code
        $verificationCode = mt_rand(100000, 999999);
        $linkPath = Str::random(50);
        $verificationLink = URL::to('/verify/email/'.$linkPath);
        if(is_null(DB::table('verifikasi')->whereRaw("BINARY email = ? AND description = 'email'",[$email])->update(['code'=>$verificationCode,'link'=>$linkPath]))){
            return ['status'=>'error','message'=>'fail create verify email'];
        }
        $data = ['name'=>$user->nama_lengkap,'email'=>$email,'code'=>$verificationCode,'link'=>urldecode($verificationLink)];
        //resend email
        Mail::to($email)->send(new VerifyEmail($data));
        return ['status'=>'success','message'=>'success send verify email','data'=>['waktu'=>Carbon::now()->addMinutes($expTime)]];
    }
    //send email forgot password
    public function createForgotPassword(Request $request, Verifikasi $verify){
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
        $userController = new MasyarakatController();
        $email = $request->input('email');
        //check email on table user
        $user = User::select('nama_lengkap')->whereRaw("BINARY email = ?",[$request->input('email')])->limit(1)->get()[0];
        if (!$user) {
            return response()->json(['status'=>'error','message'=>'Email tidak terdaftar !'],400);
        }
        //check if user have create verify email
        $verify = Verifikasi::select('send','updated_at')->whereRaw("BINARY email = ?",[$request->input('email')])->where('deskripsi', 'password')->limit(1)->get()[0];
        if (!$verify) {
            //if user haven't create email forgot password
            $verificationCode = mt_rand(100000, 999999);
            $linkPath = Str::random(50);
            $verificationLink = URL::to('/verify/password/' . $linkPath);
            $verify->email = $email;
            $verify->code = $verificationCode;
            $verify->link = $linkPath;
            $verify->description = 'changePass';
            $verify->send = 1;
            if($verify->save()){
                $data = ['name'=>$user->nama_lengkap,'email'=>$email,'code'=>$verificationCode,'link'=>$verificationLink];
                Mail::to($email)->send(new ForgotPassword($data));
                return response()->json(['status'=>'success','message'=>'kami akan kirim kode ke anda silahkan cek email','data'=>['waktu'=>Carbon::now()->addMinutes($userController->conditionOTP[0])]]);
            }else{
                return response()->json(['status'=>'error','message'=>'fail create forgot password'],500);
            }
        }
        //checking if user have create verify email
        $expTime = $userController->conditionOTP[($verify->send - 1)];
        if (!Carbon::parse($verify->updated_at)->diffInMinutes(Carbon::now()) <= $expTime) {
            return response()->json(['status'=>'error','message'=>'Kami sudah mengirim Otp silahkan cek mail anda ','data'=>true]);
        }
        //if after desired time then update code
        $verificationCode = mt_rand(100000, 999999);
        $linkPath = Str::random(50);
        $verificationLink = URL::to('/verify/password/' . $linkPath);
        if(is_null(DB::table('verify')->whereRaw("BINARY email = ? AND description = 'changePass'",[$email])->update(['code'=>$verificationCode,'link'=>$linkPath, 'updated_at' => Carbon::now()]))){
            return response()->json(['status'=>'error','message'=>'fail create forgot password'],500);
        }else{
            $data = ['name'=>$user->nama_lengkap,'email'=>$email,'code'=>$verificationCode,'link'=>$verificationLink];
            Mail::to($email)->send(new ForgotPassword($data));
            return response()->json(['status'=>'success','message'=>'email benar kami kirim kode ke anda silahkan cek email','data'=>['waktu'=>Carbon::now()->addMinutes($expTime)]]);
        }
    }
    public function verifyEmail(Request $request){
        $email = $request->input('email');
        if(empty($email) || is_null($email)){
            return response()->json(['status'=>'error','message'=>'email empty'],404);
        }else{
            $prefix = "/verify/email/";
            if(($request->path() === $prefix) && $request->isMethod("post")){
                $linkPath = substr($request->path(), strlen($prefix));
                if(Verifikasi::select("link")->whereRaw("BINARY link = ?",[$linkPath])->limit(1)->exists()){
                    if(Verifikasi::select("email")->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
                        if(is_null(DB::table('users')->whereRaw("BINARY email = ?",[$email])->update(['email_verified'=>true]))){
                            return response()->json(['status'=>'error','message'=>'error verify email'],500);
                        }else{
                            return response()->json(['status'=>'success','message'=>'email verify success']);
                        }
                    }else{
                        return response()->json(['status'=>'error','message'=>'email invalid'],400);
                    }
                }else{
                    return response()->json(['status'=>'error','message'=>'link invalid'],400);
                }
            }else{
                return response()->json(['status'=>'error','message'=>'not found'],404);
            }
        }
    }
    // public function send(){
    //     Mail::to('amirzanfikri5@gmail.com')->send(new ForgotPassword(['data'=>'data']));
    //     return view('page.home');
    // }
}
?>