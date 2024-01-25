<?php
namespace App\Http\Controllers\Mobile\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mobile\MasyarakatController;
use App\Http\Controllers\Mobile\Services\MailController;
use App\Models\User;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
class RegisterController extends Controller
{
    public function Register(Request $request, User $user, MasyarakatController $userController,MailController $mailController, Verifikasi $verify){
        $validator = Validator::make($request->only('email','password','password_confirm'), [
            'email'=>'required | email',
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
            'nama'=>'required',
        ],[
            'nama.required'=>'nama wajib di isi',
            'email.required'=>'Email wajib di isi',
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
        $email = $request->input("email");
        $pass = $request->input("password");
        $pass1 = $request->input("password_confirm");
        if (User::select("email")->whereRaw("BINARY email = ?",[$email])->limit(1)->exists()){
            return response()->json(['status'=>'error','message'=>'Email sudah digunakan'],400);
        }else if($pass !== $pass1){
            return response()->json(['status'=>'error','message'=>'Password Harus Sama'],400);
        }else{
            $result = $userController->createUser($request, $mailController, $verify);
            if($result['status'] == 'error'){
                return response()->json(['status'=>'error','message'=>$result['message']],400);
            }else{
                return response()->json(['status'=>'success','message'=>$result['message'],'data'=>$result['data']]);
            }
        }
    }
}
?>