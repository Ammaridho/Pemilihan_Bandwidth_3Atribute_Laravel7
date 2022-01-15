<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Str;

use Session;

class authController extends Controller
{
    public function signup(Request $request)
    {
        $signupCheck = User::where('username',$request->username)->first();
        if(!isset($signupCheck)){
            $signup = new User;
            $signup->username = $request->username;
            $signup->password = bcrypt($request->password);
            $signup->remember_token = Str::random(100);
            $signup->save();
            return redirect('/')->with(['success' => 'Berhasil Daftar!']);
        }else{
            return redirect('/')->with(['error' => 'Username telah dipakai!']);         
        }
        
        
    }

    public function signin(Request $request)
    {
        $data = User::where('username',$request->username)->first();
        if($data){
            if(\Hash::check($request->password, $data->password)){
                session(['session_login' => true]);
                $request->session()->put('data',$request->input());   //memasikkan inputan dari form login ke dalam data session
                Session::put('user_id', $data['id']);

                return redirect('/')->with(['success' => 'Berhasil Masuk!']);
            }
        }
        return redirect('/')->with(['error' => 'Username atau Password salah!']);
    }

    public function signout(Request $request)
    {
        $request->session()->flush();
        return redirect('/')->with(['success' => 'berhasil Logout!']);
    }
}
