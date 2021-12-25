<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Str;

class authController extends Controller
{
    public function signup(Request $request)
    {
        $signup = new User;
        $signup->username = $request->username;
        $signup->password = bcrypt($request->password);
        $signup->remember_token = Str::random(100);
        $signup->save();
        
        return redirect('/')->with(['success' => 'Berhasil Daftar!']);
    }

    public function signin(Request $request)
    {
        $data = User::where('username',$request->username)->first();
        if($data){
            if(\Hash::check($request->password, $data->password)){
                session(['session_login' => true]);
                $request->session()->put('data',$request->input());
                return redirect('/')->with(['success' => 'Berhasil Masuk!']);
            }
        }
        return redirect('/')->with(['error' => 'Username atau Password salah!']);
    }

    public function signout(Request $request)
    {
        $request->session()->flush();
        return redirect('/')->with(['success' => 'berhasil Logout!']);;
    }
}
