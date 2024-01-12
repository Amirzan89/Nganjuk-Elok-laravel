<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showPage(Request $request){
        $dataShow = [
            ''
        ];
        return view('profile',$dataShow);
    }
}