<?php
namespace App\Http\Middleware;
use Illuminate\Support\Str;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\JWTController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;
use App\Models\User;
use Closure;
class Authorizaton
{
    private $roleAdmin = ['super admin','admin event','admin seniman','admin tempat'];
    public function handle(Request $request, Closure $next){
        $role = $request->input('role');
        $path = $request->path();
        if(empty($role)){
            $validator = Validator::make($request->only('email'), [
                'email' => 'required|email',
            ], [
                'email.required' => 'Email wajib di isi',
                'email.email' => 'Email yang anda masukkan invalid',
            ]);
            if ($validator->fails()) {
                $errors = [];
                foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                    $errors[$field] = $errorMessages[0];
                }
                return response()->json(['status' => 'error', 'message' => $errors], 400);
            }
            $email = $request->input('email');
            $role = json_decode(User::select('role')->whereRaw("BINARY email = ?",[$email])->limit(1)->get(),true)[0]['role'];
        }
        //only super admin can access /admin
        if(in_array($role,['admin event','admin seniman','admin tempat','masyarakat']) && Str::startsWith($path, '/admin')){
            return response()->json(['status'=>'error','message'=>'User Unauthorized'],400);
        }
        //only super admin and admin event can access /event
        if(in_array($role,['admin seniman','admin tempat','masyarakat']) && Str::startsWith($path, '/event')){
            return response()->json(['status'=>'error','message'=>'User Unauthorized'],400);
        }
        //only super admin and admin seniman can access /seniman or /pentas
        if(in_array($role,['admin event','admin tempat','masyarakat']) && (Str::startsWith($path, '/seniman') || Str::startsWith($path, '/pentas'))){
            return response()->json(['status'=>'error','message'=>'User Unauthorized'],400);
        }
        //only super admin and admin tempat can access /tempat
        if(in_array($role,['admin event','admin seniman','masyarakat']) && Str::startsWith($path, '/tempat')){
            return response()->json(['status'=>'error','message'=>'User Unauthorized'],400);
        }
        //when admin access mobile
        if(in_array($role,$this->roleAdmin) && Str::startsWith($path, '/mobile')){
            return response()->json(['status'=>'error','message'=>'User Unauthorized'],400);
        }
        return $next($request);
    }
}