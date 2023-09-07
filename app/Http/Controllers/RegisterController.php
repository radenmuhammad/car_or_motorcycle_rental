<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\MailSend;

class RegisterController extends Controller
{
    public function register()
    {
        return view('register');
    }
    
    public function actionregister(Request $request)
    {
        $str = Str::random(100);
		if(User::where('email',$request->email)->first()){
			Session::flash('message', 'Email is already exists');			
		}else{
			$user = User::create([
				'name' => $request->name,			
				'email' => $request->email,
				'username' => $request->username,
				'password' => Hash::make($request->password),
				'role' => $request->role,
				'verify_key' => $str,
			]);	
		$user = Auth::getProvider()->retrieveByCredentials($credentials);
        Auth::login($user);			
			Session::flash('message', 'You have already registered');								
		}
        return redirect('register');
    }
    
    public function verify($verify_key)
    {
        $keyCheck = User::select('verify_key')
                    ->where('verify_key', $verify_key)
                    ->exists();
        
        if ($keyCheck) {
            $user = User::where('verify_key', $verify_key)
            ->update([
                'active' => 1
            ]);
            
            return "Verifikasi Berhasil. Akun Anda sudah aktif.";
        }else{
            return "Key tidak valid!";
        }
    }
}