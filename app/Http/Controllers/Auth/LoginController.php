<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\JwtController;
use App\Http\Controllers\Website\ChangePasswordController;
use App\Models\User;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Exception;
class LoginController extends Controller
{ 
    public function Login(Request $request){
        $refreshToken = new RefreshToken();
        $jwtController = new JWTController();
        $email = $request->input("email");
        // $email = "Admin@gmail.com";
        $pass = $request->input("password");
        $pass = "Admin@1234567890";
        if(empty($email)){
            return response()->json(['status'=>'error','message'=>'Email Tidak boleh kosong'],400);
        }else if(empty($pass)){
            return response()->json(['status'=>'error','message'=>'Password Tidak boleh kosong'],400);
        }else{
            $inPass = User::select('password')->whereRaw("BINARY email = ?",[$email])->limit(1)->get();
            $Ipass = json_decode(json_encode($inPass));
            if(!User::select("email")->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
                return response()->json(['status'=>'error','message'=>'Email Salah'],400);
            }else if(!password_verify($pass,$Ipass[0]->password)){
                return response()->json(['status'=>'error','message'=>'Password salah'],400);
            }else{
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
                        ->cookie('token1',$encoded,time()+intval(env('JWT_ACCESS_TOKEN_EXPIRED')))
                        ->cookie('token2',$data['data']['token'],time() + intval(env('JWT_ACCESS_TOKEN_EXPIRED')))
                        ->cookie('token3',$data['data']['refresh'],time() + intval(env('JWT_REFRESH_TOKEN_EXPIRED')));
                    }
                }
            }
        }
    }
    public function redirectToProvider(){
        return Socialite::driver('google')->redirect();
    }
    public function handleProviderCallback(Request $request){
        $refreshToken = new RefreshToken();
        $changePasswordController = new ChangePasswordController();
        $jwtController = new JWTController();
        try {
            $user_google = Socialite::driver('google')->stateless()->user();
            if(User::select('email')->whereRaw("BINARY email = ?",[$user_google->getEmail()])->limit(1)->exists()){
                if($request->hasCookie("token1") && $request->hasCookie("token2") && $request->hasCookie("token3")){
                    $token1 = $request->cookie('token1');
                    $token2 = $request->cookie('token2');
                    $email = base64_decode($token1);
                    $req = [
                        'email'=>$email,
                        'token'=>$token2
                    ];
                    $decoded = $jwtController->decode($req);
                    if($decoded['status'] == 'error'){
                        if($decoded['message'] == 'Expired token'){
                            $updated = $jwtController->updateTokenWebsite($email);
                            if($updated['status'] == 'error'){
                                return response()->json(['status'=>'error','message'=>$updated['message']],500);
                            }else{
                                $data1 = ['email'=>$email,'number'=>$updated['number']];
                                $encoded = base64_encode(json_encode($data1));
                                return redirect("/page/dashboard")->withCookies([
                                    cookie('token1',$encoded,time()+intval(env('JWT_ACCESS_TOKEN_EXPIRED'))),
                                    cookie('token2',$updated['data'],time() + intval(env('JWT_ACCESS_TOKEN_EXPIRED')))]);
                            }
                        }
                    }else{
                        return redirect("/dashboard")->with('json', new JsonResponse(['status'=>'success','data'=>$decoded['data'][0][0]]));
                    }
                //if user exist in database and doesnt login
                }else{
                    if(User::select('nama')->whereRaw("BINARY email = ? AND email_verified = 0",[$user_google->getEmail()])->limit(1)->exists()){
                        DB::table('users')->whereRaw("BINARY email = ?",[$user_google->getEmail()])->update(['email_verified'=>true]);
                    }
                    $data = $jwtController->createJWTWebsite($user_google->getEmail(),$refreshToken);
                    if(is_null($data)){
                        return response()->json(['status'=>'error','message'=>'create token error'],500);
                    }else{
                        if($data['status'] == 'error'){
                            return response()->json(['status'=>'error','message'=>$data['message']],400);
                        }else{
                            $encoded = base64_encode(json_encode(['email'=>$user_google->getEmail(), 'number'=>$data['number']]));
                            return redirect("/page/dashboard")->withCookies([cookie('token1',$encoded,time()+intval(env('JWT_ACCESS_TOKEN_EXPIRED'))),cookie('token2',$data['data']['token'],time() + intval(env('JWT_ACCESS_TOKEN_EXPIRED'))),cookie('token3',$data['data']['refresh'],time()+intval('JWT_REFRESH_TOKEN_EXPIRED'))]);
                        }
                    }
                }
            //if user dont exist in database
            }else{
                $data = ['email'=>$user_google->getEmail(), 'nama'=>$user_google->getName()];
                $costum = new Request();
                $costum->replace($data);
                return $changePasswordController->showVerify($costum);
            }
        } catch (\Exception $e) {
            return response()->json('Error: ' . $e->getMessage() . ', Code: ' . $e->getCode() . ', File: ' . $e->getFile() . ', Line: ' . $e->getLine());
        }
    }
    // public function getPassPage(Request $request, User $user){
    //     $validator = Validator::make($request->all(), [
    //         'email'=>'required|email',
    //         'nama'=>'required',
    //     ],[
    //         'nama.required'=>'nama wajib di isi',
    //         'email.required'=>'Email wajib di isi',
    //         'email.email'=>'Email yang anda masukkan invalid',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['status'=>'error','message'=>$validator->failed()],400);
    //     }
    //     $email = $request->input('email');
    //     $nama = $request->input('nama');
    //     return view('page.changePassword',['email'=>$email,'nama'=>$nama,'div'=>'register','description'=>'changePass','code'=>'','link'=>'']);
    // }
    public function GooglePass(Request $request, User $user, JWTController $jwtController, RefreshToken $refreshToken){
        try{
            $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:25',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
                ],
            'username'=>'required',
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
                // return response()->json(['status'=>'error','message'=>$validator->errors()->toArray()],400);
            }
            $username = $request->input('username');
            $nama = $request->input('nama');
            $email = $request->input('email');
            $password = $request->input('password');
            if (User::select("email")->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
                return response()->json(['status'=>'error','message'=>'Email sudah digunakan'],400);
            }else{
                $user->username = $username;
                $user->email = $email;
                $user->nama = $nama;
                $user->password = Hash::make($password);
                $user->email_verified = true;
                if($user->save()){
                    $data = $jwtController->createJWT($email,$refreshToken);
                    if(is_null($data)){
                        return response()->json(['status'=>'error','message'=>'create token error']);
                    }else{
                        if($data['status'] == 'error'){
                            return response()->json(['status'=>'error','message'=>$data['message']],400);
                        }else{
                            $encoded = base64_encode($email);
                            // return redirect('/dashboard');
                            return redirect("/page/dashboard")->withCookies([cookie('token1',$encoded,time()+intval(env('JWT_ACCESS_TOKEN_EXPIRED'))),cookie('token2',$data['data'],time() + intval(env('JWT_ACCESS_TOKEN_EXPIRED')))]);
                        }
                    }
                }else{
                    return response()->json(['status'=>'error','message'=>'Akun Gagal Dibuat'],400);
                }
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            // return redirect()->route('login');
        }
    }
}
?>