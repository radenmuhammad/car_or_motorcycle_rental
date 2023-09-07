<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('home');
        }else{
           return view('login');
        }
    }

    public function actionlogin(Request $request)
    {	
		$credentials = $request->only('email', 'password');	
		if (Auth::attempt($credentials)) {
			$request->session()->put('userName',$credentials['email']);			
			return Redirect::intended('home');
		}else{
			return view('login');			
		}
    }

    public function actionlogout()
    {
        Auth::logout();
        return redirect('/');
    }
}